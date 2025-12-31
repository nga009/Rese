@extends('layouts.app')

@section('title', 'マイページ - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservations/reservation.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="user-name">{{ $user->name }}さん</div>

<div class="content-wrapper">
    <div class="reservations-section">
        <h2 class="section-title">予約状況</h2>
        
        <!-- タブ -->
        <div class="tabs">
            <button class="tab-button active" data-tab="upcoming" onclick="switchTab('upcoming')">来店前</button>
            <button class="tab-button" data-tab="past" onclick="switchTab('past')">来店後</button>
        </div>

        <!-- 来店前の予約 -->
        <div id="upcoming-tab" class="tab-content active">
            @forelse($upcomingReservations as $index => $reservation)
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
                        <button onclick="showQRCode('{{ $reservation->qr_token }}', '{{ $reservation->shop->name }}')" 
                                class="edit-btn" >
                            <svg viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4.875C3 3.839 3.84 3 4.875 3h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5A1.875 1.875 0 0 1 3 9.375v-4.5ZM4.875 4.5a.375.375 0 0 0-.375.375v4.5c0 .207.168.375.375.375h4.5a.375.375 0 0 0 .375-.375v-4.5a.375.375 0 0 0-.375-.375h-4.5Zm7.875.375c0-1.036.84-1.875 1.875-1.875h4.5C20.16 3 21 3.84 21 4.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5a1.875 1.875 0 0 1-1.875-1.875v-4.5Zm1.875-.375a.375.375 0 0 0-.375.375v4.5c0 .207.168.375.375.375h4.5a.375.375 0 0 0 .375-.375v-4.5a.375.375 0 0 0-.375-.375h-4.5ZM6 6.75A.75.75 0 0 1 6.75 6h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75A.75.75 0 0 1 6 7.5v-.75Zm9.75 0A.75.75 0 0 1 16.5 6h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM3 14.625c0-1.036.84-1.875 1.875-1.875h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.035-.84 1.875-1.875 1.875h-4.5A1.875 1.875 0 0 1 3 19.125v-4.5Zm1.875-.375a.375.375 0 0 0-.375.375v4.5c0 .207.168.375.375.375h4.5a.375.375 0 0 0 .375-.375v-4.5a.375.375 0 0 0-.375-.375h-4.5Zm7.875-.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm6 0a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM6 16.5a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm9.75 0a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm-3 3a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm6 0a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <!-- QRコードモーダル -->
                        <div id="qrModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>予約QRコード</h3>
                                    <button onclick="closeQRModal()" class="close-btn">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p id="shopName" style="text-align: center; font-weight: 600; margin-bottom: 1rem;"></p>
                                    <div id="qrcode" style="text-align: center; margin: 2rem 0;"></div>
                                    <p style="text-align: center; color: #6b7280; font-size: 0.875rem;">
                                        来店時にこのQRコードを店舗スタッフに提示してください
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('reservations.edit', $reservation) }}" class="edit-btn" title="予約を編集">
                            <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" class="cancel-form" onsubmit="return confirm('予約をキャンセルしますか?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cancel-btn" title="予約をキャンセル">×</button>
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
                    @if($reservation->course)
                        <div class="detail-row">
                            <div class="detail-label">Course</div>
                            <div class="detail-value">{{ $reservation->course->name }}</div>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <p style="color: #666;">来店前の予約はありません。</p>
            @endforelse
        </div>

        <!-- 来店後の予約 -->
        <div id="past-tab" class="tab-content">
            @forelse($pastReservations as $index => $reservation)
            <div class="reservation-card">
                <div class="reservation-header">
                    <div class="reservation-number">
                        <svg class="clock-icon" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" fill="white"/>
                            <path d="M12 6v6l4 2" stroke="#4169E1" stroke-width="2" fill="none"/>
                        </svg>
                        <span>予約{{ $index + 1 }}</span>
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

                <!-- レビューセクション -->
                <div class="review-section">
                    @if($reservation->review)
                        <div class="review-display">
                            <div class="rating-display">
                                <span class="rating-label">評価：</span>
                                <div class="star">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $reservation->review->rating ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            @if($reservation->review->comment)
                                <div class="comment-display">
                                    <p class="comment-label">コメント：</p>
                                    <p class="comment-text">{{ $reservation->review->comment }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="review-form">
                            <form action="{{ route('reviews.store', $reservation) }}" method="POST">
                                @csrf
                                {{-- エラーメッセージ表示（このフォーム専用） --}}
                                @if($errors->any() && old('reservation_id') == $reservation->id)
                                    <div class="form-errors">
                                        @foreach($errors->all() as $error)
                                            <p class="error-message">{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                <div class="form-group">
                                    <label>評価</label>
                                    <div class="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $reservation->id }}-{{ $i }}">
                                            <label for="star{{ $reservation->id }}-{{ $i }}">★</label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="comment{{ $reservation->id }}">コメント</label>
                                    <textarea name="comment" id="comment{{ $reservation->id }}" rows="3" placeholder="ご感想をお聞かせください（400文字以内）">{{ old('reservation_id') == $reservation->id ? old('comment') : '' }}</textarea>
                                </div>
                                <button type="submit" class="submit-review-btn">レビューを投稿</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <p style="color: #666;">来店後の予約はありません。</p>
            @endforelse
        </div>
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

@section('scripts')
function switchTab(tabName) {
    // タブボタンの切り替え
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });
    event.target.classList.add('active');

    // タブコンテンツの切り替え
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(tabName + '-tab').classList.add('active');
}

// エラーがある場合は来店後タブを自動的に開く
@if($errors->any())
    document.querySelector('[data-tab="past"]').click();
@endif

let currentQRCode = null;

function showQRCode(token, shopName) {
    const modal = document.getElementById('qrModal');
    const qrcodeDiv = document.getElementById('qrcode');
    const shopNameEl = document.getElementById('shopName');
    
    // 既存のQRコードをクリア
    qrcodeDiv.innerHTML = '';
    
    // 店舗名を設定
    shopNameEl.textContent = shopName;
    
    // QRコードを生成
    new QRCode(qrcodeDiv, {
        text: token,
        width: 256,
        height: 256,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
    
    // モーダルを表示
    modal.style.display = 'flex';
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    modal.style.display = 'none';
}

// モーダル外クリックで閉じる
document.getElementById('qrModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});

// ESCキーで閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQRModal();
    }
});

@endsection
