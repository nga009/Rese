@extends('layouts.app')

@section('title', '予約完了 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservations/done.css')}}">
@endsection

@section('content')
<div class="main-content">
    <div class="message-card">
        <p class="message-text">ご予約ありがとうございます</p>
        <a href="{{ route('mypage') }}" class="back-btn">戻る</a>
    </div>
</div>
@endsection
