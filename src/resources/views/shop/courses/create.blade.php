@extends('layouts.app')

@section('title', 'コース作成 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/courses/create.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.courses.index', $shop) }}" class="back-link">← コース一覧に戻る</a>
        <div class="card">
            <div class="card-header">
                <h2>コース作成 - {{ $shop->name }}</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('shop.courses.store', $shop) }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">コース名</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="例: おまかせコース">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">コース説明</label>
                        <textarea id="description" name="description" placeholder="コースの内容を詳しく説明してください">{{ old('description') }}</textarea>
                        <div class="help-text">料理の内容、品数などを記載してください</div>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price">料金（円）</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="5000">
                        <div class="help-text">税込価格を入力してください</div>
                        @error('price')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" style="margin-bottom: 0;">このコースを有効にする</label>
                        </div>
                        <div class="help-text">無効にすると予約時に選択できなくなります</div>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('shop.courses.index', $shop) }}" class="btn btn-secondary">キャンセル</a>
                        <button type="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection