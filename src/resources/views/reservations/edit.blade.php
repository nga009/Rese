@extends('layouts.app')

@section('title', '予約編集 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservations/edit.css')}}">
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

                <div class="reservation-submit">
                    <button type="submit" class="submit-btn">予約を更新</button>
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