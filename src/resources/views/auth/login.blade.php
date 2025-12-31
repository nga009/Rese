@extends('layouts.app')

@section('title', 'Login - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/common.css')}}">
@endsection

@section('content')
<div class="main-content">
    <div class="login-card">
        <div class="login-header">Login</div>
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <svg class="form-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                        <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                    </svg>
                    <input type="text" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}" >
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <div class="form-group">
                    <svg class="form-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                    <input type="password" name="password" class="form-input" placeholder="Password" >
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <button type="submit" class="submit-btn">ログイン</button>
            </form>
        </div>
    </div>
</div>
@endsection
