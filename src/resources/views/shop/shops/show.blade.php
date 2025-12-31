@extends('layouts.app')

@section('title', '店舗情報詳細 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/shops/show.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="shop-header">
            <h2>{{ $shop->name }}</h2>
            <div class="action-buttons">
                <a href="{{ route('shop.shops.edit', $shop) }}" class="btn btn-editshop">店舗情報を編集</a>
                <a href="{{ route('shop.courses.index', $shop) }}" class="btn btn-course">コース管理</a>
                <a href="{{ route('shop.reservations.index', $shop) }}" class="btn btn-reservation">全予約を見る</a>
                <a href="{{ route('shop.emails.create', $shop) }}" class="btn btn-sendmail">メール送信</a>
                <a href="{{ route('shop.qr.scan', $shop) }}" class="btn btn-qrcode">QRコードスキャン</a>
                <form method="POST" action="{{ route('shop.shops.destroy', $shop) }}" onsubmit="return confirm('本当にこの店舗を削除しますか?')" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">店舗を削除</button>
                </form>
            </div>
        </div>

        <div class="grid">
            <!-- 店舗情報 -->
            <div class="card">
                <div class="card-header">
                    <h3>店舗情報</h3>
                </div>
                <div class="card-body">
                    <dl class="shop-info">
                        <dt>店舗名</dt>
                        <dd>{{ $shop->name }}</dd>
                        <dt>エリア</dt>
                        <dd>{{ $shop->area->name }}</dd>
                        <dt>ジャンル</dt>
                        <dd>{{ $shop->genre->name }}</dd>
                    </dl>
                </div>
            </div>

            <!-- 本日の予約 -->
            <div class="card">
                <div class="card-header">
                    <h3>本日の予約 ({{ $todayReservations->count() }}件)</h3>
                </div>
                <div class="card-body">
                    @if ($todayReservations->count() > 0)
                        <ul class="reservation-list">
                            @foreach ($todayReservations as $reservation)
                                <li class="reservation-item">
                                    <div class="reservation-time">
                                        {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }} - 
                                        {{ $reservation->number }}名
                                    </div>
                                    <div class="reservation-user">
                                        {{ $reservation->user->name }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            本日の予約はありません
                        </div>
                    @endif
                </div>
            </div>

            <!-- 明日の予約 -->
            <div class="card">
                <div class="card-header">
                    <h3>明日の予約 ({{ $tomorrowReservations->count() }}件)</h3>
                </div>
                <div class="card-body">
                    @if ($tomorrowReservations->count() > 0)
                        <ul class="reservation-list">
                            @foreach ($tomorrowReservations as $reservation)
                                <li class="reservation-item">
                                    <div class="reservation-time">
                                        {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }} - 
                                        {{ $reservation->number }}名
                                    </div>
                                    <div class="reservation-user">
                                        {{ $reservation->user->name }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            明日の予約はありません
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection