@extends('layouts.app')

@section('title', 'QRコードスキャン - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/reservations/scan-qr.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.shops.show', $shop) }}" class="back-link">← 店舗詳細に戻る</a>

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h2>QRコードスキャン</h2>
                <p>お客様の予約QRコードを読み取ります</p>
            </div>
            <div class="card-body">
                <div class="qr-icon">📱</div>

                <form method="POST" action="{{ route('shop.qr.verify', $shop) }}">
                    @csrf
                    <div class="form-group">
                        <label for="qr_token">QRコード</label>
                        <input type="text" 
                               id="qr_token" 
                               name="qr_token" 
                               placeholder="QRコードをスキャンまたは入力"
                               autofocus
                               required>
                        <div class="help-text">
                            QRコードリーダーでスキャンするか、手動で入力してください
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">予約を照会</button>
                </form>
            </div>
        </div>

        <div style="margin-top: 2rem; padding: 1.5rem; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 1rem; color: #111827;">使い方</h3>
            <ol style="padding-left: 1.5rem; color: #6b7280; line-height: 1.8;">
                <li>お客様にスマートフォンの予約QRコードを表示してもらいます</li>
                <li>QRコードリーダーでスキャンします</li>
                <li>自動的に予約詳細画面が表示されます</li>
            </ol>
        </div>
    </main>
@endsection