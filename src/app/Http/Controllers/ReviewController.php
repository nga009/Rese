<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Reservation $reservation)
    {
        // 自分の予約かチェック
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->back()->with('error', '不正なリクエストです');
        }

        // 既にレビュー済みかチェック
        if ($reservation->review) {
            return redirect()->back()->with('error', 'このレビューは既に投稿済みです');
        }

        Review::create([
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'shop_id' => $reservation->shop_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('mypage')->with('success', 'レビューを投稿しました');
    }
}
