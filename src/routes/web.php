<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Shop\EmailController;
use App\Http\Controllers\Shop\ShopOwnerController;
use App\Http\Controllers\Shop\CourseController;
use App\Http\Controllers\Shop\ReservationController as ShopReservationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shops.show');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // 認証完了画面へ
    return redirect()->route('verification.done');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
Route::view('/thanks', 'auth.verify-done')->name('verification.done');

// 一般ユーザー用ルート
Route::middleware(['auth', 'role:user', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/done', [ReservationController::class, 'done'])->name('reservations.done');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])
        ->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])
        ->name('reservations.update');    
    
    Route::post('/favorites/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/reviews/{reservation}', [ReviewController::class, 'store'])->name('reviews.store');

    // 決済ルート
    Route::post('/payment/checkout', [PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
    Route::get('/payment/success/{reservation}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{reservation}', [PaymentController::class, 'cancel'])->name('payment.cancel');

});

// 管理者用ルート
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/shop-owners', [AdminController::class, 'shopOwners'])->name('shop-owners.index');
    Route::get('/shop-owners/create', [AdminController::class, 'createShopOwner'])->name('shop-owners.create');
    Route::post('/shop-owners', [AdminController::class, 'storeShopOwner'])->name('shop-owners.store');
    Route::delete('/shop-owners/{user}', [AdminController::class, 'destroyShopOwner'])->name('shop-owners.destroy');
});

// 店舗代表者用ルート
Route::middleware(['auth', 'role:shop'])->prefix('shop')->name('shop.')->group(function () {
    // ダッシュボード（全店舗一覧）
    Route::get('/dashboard', [ShopOwnerController::class, 'dashboard'])->name('dashboard');
    
    // 店舗作成
    Route::get('/shops/create', [ShopOwnerController::class, 'create'])->name('shops.create');
    Route::post('/shops', [ShopOwnerController::class, 'store'])->name('shops.store');

    Route::prefix('shops/{shop}')->group(function () {
        // 店舗詳細・編集・削除
        Route::get('/', [ShopOwnerController::class, 'show'])->name('shops.show');
        Route::get('/edit', [ShopOwnerController::class, 'edit'])->name('shops.edit');
        Route::put('/', [ShopOwnerController::class, 'update'])->name('shops.update');
        Route::delete('/', [ShopOwnerController::class, 'destroy'])->name('shops.destroy');

        // 予約管理
        Route::get('/reservations', [ShopOwnerController::class, 'reservations'])->name('reservations.index');
        Route::get('/reservations/{reservation}', [ShopReservationController::class, 'show'])->name('reservations.show');

        // QRコードスキャン
        Route::get('/qr-scan', [ShopReservationController::class, 'scanQr'])->name('qr.scan');
        Route::post('/qr-verify', [ShopReservationController::class, 'verifyQr'])->name('qr.verify');

        // メール送信
        Route::get('/emails/create', [EmailController::class, 'create'])->name('emails.create');
        Route::post('/emails/send', [EmailController::class, 'send'])->name('emails.send');

        // コース管理
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    });

});