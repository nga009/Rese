<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(ReservationRequest $request)
    {
        Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'date' => $request->date,
            'time' => $request->time,
            'number' => $request->number,
        ]);

        return view('reservations.done');
    }

    public function edit(Reservation $reservation)
    {
        // 自分の予約のみ編集可能
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->load('shop');
//dd($reservation);
        return view('reservations.edit', compact('reservation'));
    }

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        // 自分の予約のみ更新可能
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->update([
            'date' => $request->date,
            'time' => $request->time,
            'number' => $request->number,
        ]);

        return redirect()->route('mypage')->with('success', '予約を更新しました。');
    }

    public function destroy(Reservation $reservation)
    {
        // 自分の予約のみ削除可能
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->delete();

        return redirect()->route('mypage')->with('success', '予約をキャンセルしました。');
    }
}
