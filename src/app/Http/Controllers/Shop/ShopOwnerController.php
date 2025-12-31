<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Http\Requests\Shop\StoreShopRequest;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopOwnerController extends Controller
{

    /**
     * 店舗代表者ダッシュボード
     */
    public function dashboard()
    {
        $user = auth()->user();
        $shops = $user->shops; // 全ての店舗を取得

        return view('shop.dashboard', compact('shops'));
    }

    /**
     * 店舗詳細（本日・明日の予約を表示）
     */
    public function show(Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        // 本日と明日の予約を取得
        $todayReservations = Reservation::where('shop_id', $shop->id)
            ->whereDate('date', today())
            ->with('user')
            ->get();

        $tomorrowReservations = Reservation::where('shop_id', $shop->id)
            ->whereDate('date', today()->addDay())
            ->with('user')
            ->get();

        return view('shop.shops.show', compact('shop', 'todayReservations', 'tomorrowReservations'));
    }

    /**
     * 店舗作成フォーム
     */
    public function create()
    {
        return view('shop.shops.create');
    }

    /**
     * 店舗を作成
     */
    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();

        // 画像アップロード
        if ($request->hasFile('image')) {
            $data['shop_image'] = $request->file('image')->store('shops', 'public');
        }

        // ユーザーIDを追加
        $data['user_id'] = auth()->id();

        Shop::create($data);

        return redirect()
            ->route('shop.dashboard')
            ->with('success', '店舗を作成しました。');
    }

    /**
     * 店舗編集フォーム
     */
    public function edit(Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗を編集する権限がありません。');
        }

        return view('shop.shops.edit', compact('shop'));
    }

    /**
     * 店舗を更新
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗を更新する権限がありません。');
        }

        $data = $request->validated();

        // 画像アップロード
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($shop->shop_image) {
                Storage::disk('public')->delete($shop->shop_image);
            }
            $data['shop_image'] = $request->file('image')->store('shops', 'public');
        }

        $shop->update($data);

        return redirect()
            ->route('shop.shops.show', $shop)
            ->with('success', '店舗情報を更新しました。');
    }

    /**
     * 店舗削除
     */
    public function destroy(Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗を削除する権限がありません。');
        }

        // 画像を削除
        if ($shop->shop_image) {
            Storage::disk('public')->delete($shop->shop_image);
        }

        $shop->delete();

        return redirect()
            ->route('shop.dashboard')
            ->with('success', '店舗を削除しました。');
    }

    /**
     * 予約一覧
     */
    public function reservations(Request $request, Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        $query = Reservation::where('shop_id', $shop->id)
            ->with('user');

        // 日付フィルター
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $reservations = $query->latest('date')
            ->latest('time')
            ->get();

        return view('shop.reservations.index', compact('reservations', 'shop'));
    }
}
