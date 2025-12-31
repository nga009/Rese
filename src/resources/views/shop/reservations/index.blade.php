@extends('layouts.app')

@section('title', '予約一覧 - Rese')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop/common.css')}}">
<link rel="stylesheet" href="{{ asset('css/shop/reservations/index.css')}}">
@endsection

@section('content')
    <main class="container">
        <a href="{{ route('shop.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>

        <div class="card">
            <div class="card-header">
                <h2>予約一覧 - {{ $shop->name }}</h2>
                <form method="GET" action="{{ route('shop.reservations.index', $shop) }}" class="filter-form">
                    <div class="filter-group">
                        <label for="date">日付</label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               value="{{ request('date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">絞り込み</button>
                </form>
            </div>

            <div class="card-body">
                @if ($reservations->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>予約日</th>
                                <th>時間</th>
                                <th>人数</th>
                                <th>予約者</th>
                                <th>決済</th>
                                <th>予約日時</th>
                                <th style="text-align: center;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date)->format('Y/m/d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                                    <td>{{ $reservation->number }}名</td>
                                    <td>{{ $reservation->user->name }}</td>
                                    <td>
                                        @if ($reservation->course_id)
                                            <span class="status-badge {{ $reservation->isPaid() ? 'status-paid' : 'status-unpaid' }}">
                                                {{ $reservation->payment_status === 'paid' ? '決済済' : '未払' }}
                                            </span>
                                        @else
                                            <span style="color: #6b7280; font-size: 0.875rem;">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $reservation->created_at->format('Y/m/d H:i') }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('shop.reservations.show', [$shop, $reservation]) }}" 
                                           class="btn-detail">
                                            詳細
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else
                    <div class="empty-state">
                        予約が見つかりませんでした
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection