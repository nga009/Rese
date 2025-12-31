@extends('layouts.app')

@section('title', '登録完了 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-done.css')}}">
@endsection

@section('content')
<div class="main-content">
    <div class="message-card">
        <p class="message-text">ご登録ありがとうございます</p>
        <a href="{{ route('login') }}" class="back-btn">ログインする</a>
    </div>
</div>
@endsection
