@extends('layouts.app')

@section('title', '店舗代表者一覧 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/admin/index.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>

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
                <h3>店舗代表者リスト</h3>
                <a href="{{ route('admin.shop-owners.create') }}" class="btn btn-primary">+ 新規作成</a>
            </div>
            <div class="card-body">
                @if ($shopOwners->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>名前 / メール</th>
                                <th>店舗</th>
                                <th>登録日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shopOwners as $owner)
                                <tr>
                                    <td>{{ $owner->id }}</td>
                                    <td>
                                        <div class="user-info">
                                            <span class="user-name">{{ $owner->name }}</span>
                                            <span class="user-email">{{ $owner->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($owner->shops)
                                            @foreach ($owner->shops as $shop)
                                                <span class="status-badge status-active">{{ $shop->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="status-badge status-inactive">未登録</span>
                                        @endif
                                    </td>
                                    <td>{{ $owner->created_at->format('Y/m/d') }}</td>
                                    <td style="text-align: center;">
                                        <form method="POST" 
                                              action="{{ route('admin.shop-owners.destroy', $owner) }}" 
                                              onsubmit="return confirm('{{ $owner->name }} さんを削除してもよろしいですか?');"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <h3>店舗代表者がまだ登録されていません</h3>
                        <p>「+ 新規作成」ボタンから最初の店舗代表者を作成しましょう</p>
                        <a href="{{ route('admin.shop-owners.create') }}" class="btn btn-primary">新規作成</a>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
