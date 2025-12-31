@extends('layouts.app')

@section('title', 'コース管理 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/courses/index.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.shops.show', $shop) }}" class="back-link">← 店舗詳細に戻る</a>

        <div class="page-header">
            <div>
                <h2>コース管理 - {{ $shop->name }}</h2>
                <p style="color: #6b7280; margin-top: 0.5rem;">コースを作成・編集できます</p>
            </div>
            <a href="{{ route('shop.courses.create', $shop) }}" class="btn btn-primary">+ 新しいコースを作成</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($courses->count() > 0)
            <div class="courses-grid">
                @foreach ($courses as $course)
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-name">{{ $course->name }}</div>
                            <div class="course-status {{ $course->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $course->is_active ? '有効' : '無効' }}
                            </div>
                        </div>
                        <div class="course-price">{{ $course->formatted_price }}</div>
                        <div class="course-description">{{ $course->description }}</div>
                        <div class="course-actions">
                            <a href="{{ route('shop.courses.edit', [$shop, $course]) }}" class="btn btn-small btn-edit">編集</a>
                            <form method="POST" action="{{ route('shop.courses.destroy', [$shop, $course]) }}" onsubmit="return confirm('本当に削除しますか?')" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger" style="width: 100%;">削除</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🍽️</div>
                <h3>コースがまだ登録されていません</h3>
                <p>「+ 新しいコースを作成」ボタンから最初のコースを登録しましょう</p>
                <a href="{{ route('shop.courses.create', $shop) }}" class="btn btn-primary">コースを作成</a>
            </div>
        @endif
    </main>
@endsection