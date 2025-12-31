@extends('layouts.app')

@section('title', '予約編集 - Rese')

@section('styles')
<style>
    .content-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px;
        display: flex;
        gap: 60px;
    }

    .shop-detail {
        flex: 1;
    }

    .back-button {
        background: white;
        border: none;
        border-radius: 5px;
        width: 40px;
        height: 40px;
        font-size: 20px;
        cursor: pointer;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .back-button:hover {
        background-color: #f5f5f5;
    }

    .page-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .shop-name {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #666;
    }

    .shop-image {
        width: 100%;
        max-width: 650px;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .shop-tags {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }

    .shop-description {
        font-size: 15px;
        line-height: 1.8;
        color: #333;
    }

    .reservation-section {
        flex: 0 0 500px;
    }

    .reservation-card {
        background: #4169E1;
        border-radius: 8px;
        padding: 40px;
        color: white;
    }

    .reservation-title {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        outline: none;
    }

    .error-message {
        color: #ffcccc;
        font-size: 12px;
        margin-top: 5px;
    }

    .reservation-summary {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        padding: 25px;
        margin: 30px 0;
    }

    .summary-row {
        display: flex;
        margin-bottom: 15px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-label {
        width: 100px;
        font-weight: normal;
    }

    .summary-value {
        font-weight: normal;
    }

    .button-group {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .submit-btn {
        flex: 1;
        background-color: #0047AB;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 15px;
        font-size: 16px;
        cursor: pointer;
    }

    .submit-btn:hover {
        background-color: #003380;
    }

    .cancel-link {
        flex: 1;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 15px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cancel-link:hover {
        background-color: #5a6268;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .reservation-section {
            flex: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="shop-detail">
        <button class="back-button" onclick="history.back()">&lt;</button>
        <h1 class="page-title">予約の編集</h1>
        <h2 class="shop-name">{{ $reservation->shop->name }}</h2>
        <img src="{{ asset('storage/' . $reservation->shop->shop_image) }}" alt="{{ $reservation->shop->name }}" class="shop-image">
        <p class="shop-tags">#{{ $reservation->shop->area->name }} #{{ $reservation->shop->genre->name }}</p>
        <p class="shop-description">{{ $reservation->shop->description }}</p>
    </div>

    <div class="reservation-section">
        <div class="reservation-card">
            <h2 class="reservation-title">予約内容の変更</h2>
            <form method="POST" action="{{ route('reservations.update', $reservation) }}" id="reservationForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ old('id') }}" >
                <input type="hidden" name="shop_id" value="{{ old('shop_id', $reservation->shop_id ) }}" >
                <div class="form-group">
                    <input type="date" name="date" class="form-input" id="dateInput" 
                           value="{{ old('date', $reservation->date->format('Y-m-d')) }}" required>
                    @error('date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <input type="time" name="time" class="form-input" id="timeInput" 
                           value="{{ old('time', \Carbon\Carbon::parse($reservation->time)->format('H:i')) }}" required>
                    @error('time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <select name="number" class="form-select" id="numberInput" required>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('number', $reservation->number) == $i ? 'selected' : '' }}>{{ $i }}人</option>
                        @endfor
                    </select>
                    @error('number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="reservation-summary">
                    <div class="summary-row">
                        <div class="summary-label">Shop</div>
                        <div class="summary-value">{{ $reservation->shop->name }}</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Date</div>
                        <div class="summary-value" id="summaryDate">{{ old('date', $reservation->date->format('Y-m-d')) }}</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Time</div>
                        <div class="summary-value" id="summaryTime">{{ old('time', \Carbon\Carbon::parse($reservation->time)->format('H:i')) }}</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Number</div>
                        <div class="summary-value" id="summaryNumber">{{ old('number', $reservation->number) }}人</div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">予約を更新</button>
                    <a href="{{ route('mypage') }}" class="cancel-link">キャンセル</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
const dateInput = document.getElementById('dateInput');
const timeInput = document.getElementById('timeInput');
const numberInput = document.getElementById('numberInput');
const summaryDate = document.getElementById('summaryDate');
const summaryTime = document.getElementById('summaryTime');
const summaryNumber = document.getElementById('summaryNumber');

if (dateInput && summaryDate) {
    dateInput.addEventListener('change', () => {
        summaryDate.textContent = dateInput.value;
    });
}

if (timeInput && summaryTime) {
    timeInput.addEventListener('change', () => {
        summaryTime.textContent = timeInput.value;
    });
}

if (numberInput && summaryNumber) {
    numberInput.addEventListener('change', () => {
        summaryNumber.textContent = numberInput.options[numberInput.selectedIndex].text;
    });
}
@endsection