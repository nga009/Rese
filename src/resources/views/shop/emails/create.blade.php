@extends('layouts.app')

@section('title', 'メール送信 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/emails/create.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h2>送信対象ユーザー数</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-card-title">過去に予約したユーザー</div>
                        <div class="info-card-value">{{ $pastUsersCount }}人</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">明日予約しているユーザー</div>
                        <div class="info-card-value">{{ $tomorrowUsersCount }}人</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">本日予約しているユーザー</div>
                        <div class="info-card-value">{{ $todayUsersCount }}人</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>メール送信</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('shop.emails.send', $shop) }}">
                    @csrf

                    <div class="form-group">
                        <label>
                            送信対象
                        </label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" 
                                       name="target" 
                                       value="past" 
                                       {{ old('target') == 'past' ? 'checked' : '' }}
                                       >
                                <span class="radio-label">
                                    過去に予約したことがあるユーザー
                                    <span class="target-count">({{ $pastUsersCount }}人)</span>
                                </span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" 
                                       name="target" 
                                       value="tomorrow" 
                                       {{ old('target') == 'tomorrow' ? 'checked' : '' }}
                                       >
                                <span class="radio-label">
                                    明日予約しているユーザー
                                    <span class="target-count">({{ $tomorrowUsersCount }}人)</span>
                                </span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" 
                                       name="target" 
                                       value="today" 
                                       {{ old('target') == 'today' ? 'checked' : '' }}
                                       >
                                <span class="radio-label">
                                    本日予約しているユーザー
                                    <span class="target-count">({{ $todayUsersCount }}人)</span>
                                </span>
                            </label>
                        </div>
                        @error('target')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            件名
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}" 
                               placeholder="例: 【{{ $shop->name }}】特別キャンペーンのお知らせ"
                               >
                        @error('subject')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">
                            メッセージ
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  placeholder="お客様へのメッセージを入力してください"
                                  >{{ old('message') }}</textarea>
                        <div class="help-text">最大2000文字まで入力できます</div>
                        @error('message')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('shop.dashboard') }}" class="btn btn-secondary">キャンセル</a>
                        <button type="submit" 
                                class="btn btn-primary"
                                onclick="return confirm('選択したユーザーにメールを送信します。よろしいですか?')">
                            送信する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection