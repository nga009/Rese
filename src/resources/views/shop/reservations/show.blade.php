@extends('layouts.app')

@section('title', '予約詳細 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/reservations/show.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.reservations.index', $shop) }}" class="back-link">← 予約一覧に戻る</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h2>予約詳細</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-label">予約ID</div>
                    <div class="info-value">#{{ $reservation->id }}</div>

                    <div class="info-label">予約者名</div>
                    <div class="info-value">{{ $reservation->user->name }}</div>

                    <div class="info-label">予約日</div>
                    <div class="info-value">{{ $reservation->date->format('Y/m/d') }}</div>

                    <div class="info-label">予約時間</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</div>

                    <div class="info-label">人数</div>
                    <div class="info-value">{{ $reservation->number }}名</div>

                    <div class="info-label">決済ステータス</div>
                    <div class="info-value">
                        <span class="status-badge {{ $reservation->isPaid() ? 'status-paid' : 'status-unpaid' }}">
                            {{ $reservation->payment_status === 'paid' ? '決済済' : '未払' }}
                        </span>
                    </div>

                    <div class="info-label">予約日時</div>
                    <div class="info-value">{{ $reservation->created_at->format('Y/m/d H:i') }}</div>
                </div>

                @if ($reservation->course)
                    <div class="course-info">
                        <h3>選択コース</h3>
                        <div class="info-grid">
                            <div class="info-label">コース名</div>
                            <div class="info-value">{{ $reservation->course->name }}</div>

                            <div class="info-label">コース説明</div>
                            <div class="info-value">{{ $reservation->course->description }}</div>

                            <div class="info-label">料金</div>
                            <div class="info-value">
                                <span class="price">{{ $reservation->formatted_total_amount }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection