<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\SendEmailRequest;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\User;
use App\Mail\ShopNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * メール送信フォーム
     */
    public function create(Shop $shop)
    {
        // 自分の店舗かチェック
        if (!$shop) {
            return redirect()->route('shop.dashboard')
                ->with('error', '店舗を登録してください。');
        }

        // 各ターゲットの人数を取得
        $pastUsersCount = $this->getPastUsers($shop->id)->count();
        $tomorrowUsersCount = $this->getTomorrowUsers($shop->id)->count();
        $todayUsersCount = $this->getTodayUsers($shop->id)->count();

        return view('shop.emails.create', compact(
            'shop',
            'pastUsersCount',
            'tomorrowUsersCount',
            'todayUsersCount'
        ));
    }

    /**
     * メールを送信
     */
    public function send(SendEmailRequest $request, Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        $target = $request->target;
        $subject = $request->subject;
        $message = $request->message;

        // ターゲットに応じてユーザーを取得
        $users = match ($target) {
            'past' => $this->getPastUsers($shop->id),
            'tomorrow' => $this->getTomorrowUsers($shop->id),
            'today' => $this->getTodayUsers($shop->id),
            default => collect([]),
        };

        if ($users->isEmpty()) {
            return back()->with('error', '送信対象のユーザーがいません。');
        }

        // メール送信
        foreach ($users as $user) {
            Mail::to($user->email)->send(
                new ShopNotification($shop, $subject, $message)
            );
        }

        return redirect()
            ->route('shop.emails.create', $shop)
            ->with('success', "{$users->count()}人にメールを送信しました。");
    }

    /**
     * 過去に予約したことがあるユーザーを取得
     */
    private function getPastUsers($shopId)
    {
        return User::whereHas('reservations', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        })->distinct()->get();
    }

    /**
     * 明日予約しているユーザーを取得
     */
    private function getTomorrowUsers($shopId)
    {
        return User::whereHas('reservations', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId)
                ->whereDate('date', today()->addDay());
        })->distinct()->get();
    }

    /**
     * 今日予約しているユーザーを取得
     */
    private function getTodayUsers($shopId)
    {
        return User::whereHas('reservations', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId)
                ->whereDate('date', today());
        })->distinct()->get();
    }
}
