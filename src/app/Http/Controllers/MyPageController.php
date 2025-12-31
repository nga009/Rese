<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


class MyPageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();

        // 来店前の予約（予約日時が現在より未来）
        $upcomingReservations = $user->reservations()
            ->with(['shop', 'review', 'course'])
            ->where(function($query) use ($now) {
                $query->where('date', '>', $now->toDateString())
                    ->orWhere(function($q) use ($now) {
                        $q->where('date', '=', $now->toDateString())
                          ->where('time', '>=', $now->toTimeString());
                    });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        // 来店後の予約（予約日時が現在より過去）
        $pastReservations = $user->reservations()
            ->with(['shop', 'review'])
            ->where(function($query) use ($now) {
                $query->where('date', '<', $now->toDateString())
                    ->orWhere(function($q) use ($now) {
                        $q->where('date', '=', $now->toDateString())
                          ->where('time', '<', $now->toTimeString());
                    });
            })
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        // お気に入り店舗
        $favoriteShops = $user->favoriteShops()
            ->with(['area', 'genre'])
            ->get();

        return view('mypage.index', compact('user', 'upcomingReservations', 'pastReservations', 'favoriteShops'));
    }
}
