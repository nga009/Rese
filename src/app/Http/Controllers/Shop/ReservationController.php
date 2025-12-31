<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * 予約詳細（店舗代表者用）
     */
    public function show(Shop $shop, Reservation $reservation)
    {
        // 自分の店舗の予約かチェック
        if ($shop->user_id !== auth()->id() || $reservation->shop_id !== $shop->id) {
            abort(403, 'この予約にアクセスする権限がありません。');
        }

        return view('shop.reservations.show', compact('shop', 'reservation'));
    }

    /**
     * QRコードスキャン画面
     */
    public function scanQr(Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        return view('shop.reservations.scan-qr', compact('shop'));
    }

    /**
     * QRコードから予約を照会
     */
    public function verifyQr(Request $request, Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        // QRトークンをデコード
        $decoded = base64_decode($request->qr_token);
        $parts = explode(':', $decoded);

        if (count($parts) !== 2) {
            return back()->with('error', '無効なQRコードです。');
        }

        $reservationId = $parts[0];

        // 予約を取得
        $reservation = Reservation::with(['user', 'course'])
            ->where('id', $reservationId)
            ->where('shop_id', $shop->id)
            ->first();

        if (!$reservation) {
            return back()->with('error', '予約が見つかりませんでした。');
        }

        return redirect()->route('shop.reservations.show', [$shop, $reservation])
            ->with('success', '予約を確認しました。');
    }
}
