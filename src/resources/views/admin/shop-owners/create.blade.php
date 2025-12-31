@extends('layouts.app')

@section('title', '店舗代表者作成 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/admin/create.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>
        <div class="card">
            <div class="card-header">
                <h2>店舗代表者作成</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.shop-owners.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">
                            名前
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
                        <label for="email">
                            メールアドレス
                        </label>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">
                            パスワード
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               >
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">キャンセル</a>
                        <button type="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection