@extends('layouts.app')

@section('title', '管理者ダッシュボード - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/common.css')}}">
@endsection

@section('content')
    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3>最近作成された店舗代表者</h3>
            </div>
            <div class="card-body">
                @if ($shopOwners->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名前</th>
                                <th>メールアドレス</th>
                                <th>店舗</th>
                                <th>作成日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shopOwners as $owner)
                                <tr>
                                    <td>{{ $owner->id }}</td>
                                    <td>{{ $owner->name }}</td>
                                    <td>{{ $owner->email }}</td>
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
                                    <td>
                                        <form method="POST" action="{{ route('admin.shop-owners.destroy', $owner) }}" 
                                              onsubmit="return confirm('本当に削除しますか?');">
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
                    <p style="text-align: center; color: #6b7280; padding: 2rem;">
                        まだ店舗代表者が登録されていません
                    </p>
                @endif
            </div>
        </div>
    </main>
@endsection