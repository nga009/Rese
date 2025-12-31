<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Stripe決済セッション作成
     */
    public function createCheckoutSession(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => ['required', 'exists:shops,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required'],
            'number' => ['required', 'integer', 'min:1'],
        ]);

        $shop = Shop::findOrFail($validated['shop_id']);
        $course = Course::findOrFail($validated['course_id']);

        // コースが店舗に属しているかチェック
        if ($course->shop_id !== $shop->id) {
            return back()->with('error', 'コースが見つかりません。');
        }

        // 仮予約を作成
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $shop->id,
            'course_id' => $course->id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'number' => $validated['number'],
            'payment_status' => 'unpaid',
            'total_amount' => $course->price,
        ]);

        // Stripe Checkoutセッション作成
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $shop->name . ' - ' . $course->name,
                        'description' => $course->description,
                    ],
                    'unit_amount' => $course->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['reservation' => $reservation->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel', ['reservation' => $reservation->id]),
            'metadata' => [
                'reservation_id' => $reservation->id,
            ],
        ]);

        return redirect($session->url);
    }

    /**
     * 決済成功
     */
    public function success(Request $request, Reservation $reservation)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('mypage')->with('error', 'セッションIDが見つかりません。');
        }

        try {
            $session = StripeSession::retrieve($sessionId);

            // 決済が完了しているか確認
            if ($session->payment_status === 'paid') {
                $reservation->update([
                    'payment_status' => 'paid',
                    'stripe_payment_intent_id' => $session->payment_intent,
                ]);

                return redirect()->route('mypage')
                    ->with('success', '決済が完了しました。予約を確定しました。');
            }
        } catch (\Exception $e) {
            \Log::error('Stripe payment verification failed: ' . $e->getMessage());
        }

        return redirect()->route('mypage')
            ->with('error', '決済の確認に失敗しました。');
    }

    /**
     * 決済キャンセル
     */
    public function cancel(Reservation $reservation)
    {
        // 未払いの予約を削除
        if ($reservation->payment_status === 'unpaid') {
            $reservation->delete();
        }

        return redirect()->route('shops.show', $reservation->shop_id)
            ->with('error', '決済がキャンセルされました。');
    }
}
