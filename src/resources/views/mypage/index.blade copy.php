@extends('layouts.app')

@section('title', 'マイページ - Rese')

@section('styles')
<style>
    .user-name {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        margin: 40px 0;
    }

    .content-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
        display: flex;
        gap: 60px;
    }

    .reservations-section {
        flex: 0 0 500px;
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .reservation-card {
        background: #4169E1;
        color: white;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 20px;
    }

    .reservation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .reservation-number {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
    }

    .clock-icon {
        width: 24px;
        height: 24px;
    }

    .cancel-form {
        display: inline;
    }

    .cancel-btn {
        background: none;
        border: 2px solid white;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cancel-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .reservation-details {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .detail-row {
        display: flex;
        gap: 40px;
    }

    .detail-label {
        width: 80px;
        font-weight: normal;
    }

    .detail-value {
        font-weight: normal;
    }

    .favorites-section {
        flex: 1;
    }

    .shops-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .shop-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .shop-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .shop-info {
        padding: 15px;
    }

    .shop-name {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .shop-tags {
        font-size: 12px;
        color: #666;
        margin-bottom: 15px;
    }

    .shop-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-btn {
        background-color: #4169E1;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 20px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .detail-btn:hover {
        background-color: #3454b4;
    }

    .favorite-form {
        display: inline;
    }

    .favorite-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 24px;
        color: #ff0000;
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 12px 20px;
        border-radius: 5px;
        margin: 20px auto;
        max-width: 500px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .reservations-section {
            flex: 1;
        }
    }
</style>
@endsection

@section('content')
@if(session('success'))
    <div class="success-message">{{ session('success') }}</div>
@endif


<div class="content-wrapper">
    <div class="reservations-section">
        <h2 class="section-title">予約状況</h2>
        @forelse($reservations as $index => $reservation)
        <div class="reservation-card">
            <div class="reservation-header">
                <div class="reservation-number">
                    <svg class="clock-icon" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" fill="white"/>
                        <path d="M12 6v6l4 2" stroke="#4169E1" stroke-width="2" fill="none"/>
                    </svg>
                    <span>予約{{ $index + 1 }}</span>
                </div>
                <div class="reservation-actions">
                    <a href="{{ route('reservations.edit', $reservation) }}" class="edit-btn" title="予約を編集">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" class="cancel-form" onsubmit="return confirm('予約をキャンセルしますか?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cancel-btn">×</button>
                    </form>
                </div>
            </div>
            <div class="reservation-details">
                <div class="detail-row">
                    <div class="detail-label">Shop</div>
                    <div class="detail-value">{{ $reservation->shop->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">{{ $reservation->date->format('Y-m-d') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Number</div>
                    <div class="detail-value">{{ $reservation->number }}人</div>
                </div>
            </div>
        </div>
        @empty
        <p style="color: #666;">予約はありません。</p>
        @endforelse
    </div>

    <div class="favorites-section">
        <h2 class="section-title">お気に入り店舗</h2>
        <div class="shops-grid">
            @forelse($favoriteShops as $shop)
            <div class="shop-card">
                <img src="{{ asset('storage/' . $shop->shop_image) }}" alt="{{ $shop->name }}" class="shop-image">
                <div class="shop-info">
                    <div class="shop-name">{{ $shop->name }}</div>
                    <div class="shop-tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</div>
                    <div class="shop-actions">
                        <a href="{{ route('shops.show', $shop) }}" class="detail-btn">詳しくみる</a>
                        <form method="POST" action="{{ route('favorites.toggle', $shop) }}" class="favorite-form">
                            @csrf
                            <button type="submit" class="favorite-btn">♥</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p style="color: #666;">お気に入り店舗はありません。</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
