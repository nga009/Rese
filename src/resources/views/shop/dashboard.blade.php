@extends('layouts.app')

@section('title', '店舗代表者ダッシュボード - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
@endsection

@section('content')
    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('shop.shops.create') }}" class="btn btn-primary">+ 新しい店舗を作成</a>
        </div>

        @if ($shops->count() > 0)
            <div class="shops-grid">
                @foreach ($shops as $shop)
                    <div class="shop-card">
                        @if ($shop->shop_image)
                            <img src="{{ asset('storage/' . $shop->shop_image) }}" alt="{{ $shop->name }}" class="shop-image">
                        @else
                            <div class="shop-image" style="display: flex; align-items: center; justify-content: center; background-color: #e5e7eb; color: #9ca3af;">
                                画像なし
                            </div>
                        @endif
                        <div class="shop-content">
                            <div class="shop-name">{{ $shop->name }}</div>
                            <div class="shop-info">
                                <div class="shop-info-item">
                                    <span class="shop-info-label">エリア:</span>
                                    <span>{{ $shop->area->name }}</span>
                                </div>
                                <div class="shop-info-item">
                                    <span class="shop-info-label">ジャンル:</span>
                                    <span>{{ $shop->genre->name }}</span>
                                </div>
                            </div>
                            <div class="shop-actions">
                                <a href="{{ route('shop.shops.show', $shop) }}" class="btn-small btn-view">詳細</a>
                                <a href="{{ route('shop.shops.edit', $shop) }}" class="btn-small btn-edit">編集</a>
                                <a href="{{ route('shop.emails.create', $shop) }}" class="btn-small btn-email">メール</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>店舗がまだ登録されていません</h3>
                <p>「+ 新しい店舗を作成」ボタンから最初の店舗を登録しましょう</p>
                <a href="{{ route('shop.shops.create') }}" class="btn btn-primary">店舗を作成</a>
            </div>
        @endif
    </main>
@endsection
