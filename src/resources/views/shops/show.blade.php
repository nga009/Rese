@extends('layouts.app')

@section('title', $shop->name . ' - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops/show.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="shop-detail">
        <div class="shop-detail-header">
            <button class="back-button" onclick="history.back()">&lt;</button>
            <h1 class="shop-name">{{ $shop->name }}</h1>
        </div>
        <img src="{{ asset('storage/' . $shop->shop_image) }}" alt="{{ $shop->name }}" class="shop-image">
        <p class="shop-tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p class="shop-description">{{ $shop->description }}</p>
    </div>

    @auth
    <div class="reservation-section">
        <div class="reservation-card">
            <h2 class="reservation-title">予約</h2>
            <form method="POST" action="{{ route('reservations.store') }}" id="reservationForm">
                @csrf
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <input type="hidden" name="course_id" id="courseIdInput" value="">
                
                <div class="form-group">
                    <input type="date" name="date" class="form-input" id="dateInput" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                    @error('date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <input type="time" name="time" class="form-input" id="timeInput" value="{{ old('time', '17:00') }}" required>
                    @error('time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <select name="number" class="form-select" id="numberInput" required>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('number', 1) == $i ? 'selected' : '' }}>{{ $i }}人</option>
                        @endfor
                    </select>
                    @error('number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- コース選択（該当店舗にコースがある場合のみ表示） --}}
                @if($shop->activeCourses && $shop->activeCourses->count() > 0)
                <div class="form-group course-section">
                    <div class="course-section-title">コース選択（任意）</div>
                    <div class="course-options">
                        {{-- コースなしオプション --}}
                        <label class="course-option selected" data-course-id="" data-course-price="0">
                            <div class="course-header">
                                <input type="radio" name="course_radio" value="" checked>
                                <span class="course-name">コースなし</span>
                            </div>
                        </label>

                        {{-- 各コースオプション --}}
                        @foreach($shop->activeCourses as $course)
                        <label class="course-option" data-course-id="{{ $course->id }}" data-course-price="{{ $course->price }}">
                            <div class="course-header">
                                <input type="radio" name="course_radio" value="{{ $course->id }}">
                                <span class="course-name">{{ $course->name }}</span>
                            </div>
                            <div class="course-price">{{ $course->formatted_price }}</div>
                            @if($course->description)
                            <div class="course-description">{{ $course->description }}</div>
                            @endif
                        </label>
                        @endforeach
                    </div>

                    {{-- 合計金額表示 --}}
                    <div class="total-amount-display" id="totalAmountDisplay">
                        <div class="total-amount-label">お支払い金額</div>
                        <div class="total-amount-value" id="totalAmountValue">¥0</div>
                    </div>
                </div>
                @endif

                <div class="reservation-summary">
                    <div class="summary-row">
                        <div class="summary-label">Shop</div>
                        <div class="summary-value">{{ $shop->name }}</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Date</div>
                        <div class="summary-value" id="summaryDate">{{ old('date', now()->format('Y-m-d')) }}</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Time</div>
                        <div class="summary-value" id="summaryTime">{{ old('time', '17:00') }}</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Number</div>
                        <div class="summary-value" id="summaryNumber">{{ old('number', 1) }}人</div>
                    </div>
                    <div class="summary-row" id="summaryCourseLine" style="display: none;">
                        <div class="summary-label">Course</div>
                        <div class="summary-value" id="summaryCourse">-</div>
                    </div>
                </div>
                <div class="reservation-submit">
                    <button type="submit" class="submit-btn" id="submitBtn">予約する</button>
                </div>
            </form>
        </div>
    </div>
    @endauth
</div>
@endsection

@section('scripts')
// 既存の要素
const dateInput = document.getElementById('dateInput');
const timeInput = document.getElementById('timeInput');
const numberInput = document.getElementById('numberInput');
const summaryDate = document.getElementById('summaryDate');
const summaryTime = document.getElementById('summaryTime');
const summaryNumber = document.getElementById('summaryNumber');

// コース関連の要素
const courseOptions = document.querySelectorAll('.course-option');
const courseIdInput = document.getElementById('courseIdInput');
const totalAmountDisplay = document.getElementById('totalAmountDisplay');
const totalAmountValue = document.getElementById('totalAmountValue');
const summaryCourseLine = document.getElementById('summaryCourseLine');
const summaryCourse = document.getElementById('summaryCourse');
const submitBtn = document.getElementById('submitBtn');
const reservationForm = document.getElementById('reservationForm');

let selectedCourseId = '';
let selectedCoursePrice = 0;
let selectedCourseName = '';

// 既存の日付変更イベント
if (dateInput && summaryDate) {
    dateInput.addEventListener('change', () => {
        summaryDate.textContent = dateInput.value;
    });
}

// 既存の時間変更イベント
if (timeInput && summaryTime) {
    timeInput.addEventListener('change', () => {
        summaryTime.textContent = timeInput.value;
    });
}

// 既存の人数変更イベント
if (numberInput && summaryNumber) {
    numberInput.addEventListener('change', () => {
        summaryNumber.textContent = numberInput.options[numberInput.selectedIndex].text;
    });
}

// コース選択イベント
if (courseOptions) {
    courseOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            // ラベルのクリックでラジオボタンが自動的にチェックされるため、
            // 手動での処理は不要だが、UIの更新は必要
            
            // 全ての選択を解除
            courseOptions.forEach(opt => opt.classList.remove('selected'));
            
            // クリックされたオプションを選択
            this.classList.add('selected');
            
            // 選択されたコース情報を取得
            selectedCourseId = this.dataset.courseId || '';
            selectedCoursePrice = parseInt(this.dataset.coursePrice) || 0;
            selectedCourseName = this.querySelector('.course-name')?.textContent || '';
            
            // hidden inputに設定
            if (courseIdInput) {
                courseIdInput.value = selectedCourseId;
            }
            
            // UIを更新
            updateReservationUI();
        });
    });
}

function updateReservationUI() {
    if (selectedCourseId && selectedCoursePrice > 0) {
        // コースが選択されている場合
        
        // 合計金額を表示
        if (totalAmountDisplay) {
            totalAmountDisplay.classList.add('show');
        }
        if (totalAmountValue) {
            totalAmountValue.textContent = '¥' + selectedCoursePrice.toLocaleString();
        }
        
        // サマリーにコース名を表示
        if (summaryCourseLine) {
            summaryCourseLine.style.display = 'flex';
        }
        if (summaryCourse) {
            summaryCourse.textContent = selectedCourseName;
        }
        
        // ボタンを決済用に変更
        if (submitBtn) {
            submitBtn.textContent = '決済に進む';
            submitBtn.classList.add('with-payment');
        }
        
        // フォームアクションをStripe決済に変更
        if (reservationForm) {
            reservationForm.action = '{{ route("payment.checkout") }}';
        }
        
        // name属性を変更（Stripe用）
        if (dateInput) dateInput.name = 'date';
        if (timeInput) timeInput.name = 'time';
        if (numberInput) numberInput.name = 'number';
        
    } else {
        // コースが選択されていない場合（通常予約）
        
        // 合計金額を非表示
        if (totalAmountDisplay) {
            totalAmountDisplay.classList.remove('show');
        }
        
        // サマリーのコース行を非表示
        if (summaryCourseLine) {
            summaryCourseLine.style.display = 'none';
        }
        
        // ボタンを通常予約に戻す
        if (submitBtn) {
            submitBtn.textContent = '予約する';
            submitBtn.classList.remove('with-payment');
        }
        
        // フォームアクションを通常予約に戻す
        if (reservationForm) {
            reservationForm.action = '{{ route("reservations.store") }}';
        }
        
        // name属性を戻す（通常予約用）
        if (dateInput) dateInput.name = 'date';
        if (timeInput) timeInput.name = 'time';
        if (numberInput) numberInput.name = 'number';
    }
}

// ページ読み込み時に初期化
document.addEventListener('DOMContentLoaded', function() {
    updateReservationUI();
});
@endsection
