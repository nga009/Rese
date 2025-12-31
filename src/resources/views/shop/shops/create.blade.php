@extends('layouts.app')

@section('title', '店舗作成 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/shops/create.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>

        <div class="card">
            <div class="card-header">
                <h2>店舗情報登録</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('shop.shops.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name">
                            店舗名
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               >
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="area_id">
                            エリア
                        </label>
                        <select id="area_id" name="area_id">
                            <option value="">選択してください</option>
                            @foreach (\App\Models\Area::all() as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="genre_id">
                            ジャンル
                        </label>
                        <select id="genre_id" name="genre_id">
                            <option value="">選択してください</option>
                            @foreach (\App\Models\Genre::all() as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('genre_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">
                            店舗説明
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  >{{ old('description') }}</textarea>
                        <div class="help-text">店舗の特徴や雰囲気を詳しく説明してください</div>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">
                            店舗画像
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   >
                        </div>
                        <div class="help-text">JPEG、PNG形式のみ（最大2MB）</div>
                        @error('image')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('shop.dashboard') }}" class="btn btn-secondary">キャンセル</a>
                        <button type="submit" class="btn btn-primary">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection