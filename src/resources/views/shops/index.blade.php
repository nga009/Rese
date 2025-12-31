@extends('layouts.app')

@section('title', 'Rese - 店舗一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops/index.css')}}">
@endsection

@section('content')
<div class="search-bar-wrapper">
    <form method="GET" action="{{ route('shops.index') }}" class="search-bar">
        <select name="area_id" class="search-select" onchange="this.form.submit()">
            <option value="">All area</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </select>
        <div class="search-divider"></div>
        <select name="genre_id" class="search-select" onchange="this.form.submit()">
            <option value="">All genre</option>
            @foreach($genres as $genre)
                <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
            @endforeach
        </select>
        <div class="search-divider"></div>
        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="keyword" class="search-input" placeholder="Search ..." value="{{ request('keyword') }}">
    </form>
</div>
<div class="shops-container">
    <div class="shops-grid">
        @foreach($shops as $shop)
        <div class="shop-card">
            <img src="{{ asset('storage/' . $shop->shop_image) }}" alt="{{ $shop->name }}" class="shop-image">
            <div class="shop-info">
                <div class="shop-name">{{ $shop->name }}</div>
                <div class="shop-tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</div>
                <div class="shop-actions">
                    <a href="{{ route('shops.show', $shop) }}" class="detail-btn">詳しくみる</a>
                    @auth
                        <form method="POST" action="{{ route('favorites.toggle', $shop) }}" class="favorite-form">
                            @csrf
                            <button type="submit" class="favorite-btn {{ auth()->user()->hasFavorited($shop) ? 'active' : '' }}">
                                {{ auth()->user()->hasFavorited($shop) ? '♥' : '♡' }}
                            </button>
                        </form>
                    @else
                        <button type="button" class="favorite-btn">♡</button>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
