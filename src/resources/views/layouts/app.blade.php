<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rese')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
    <style>
        @yield('styles')
    </style>
</head>
<body>
    <div class="header">
        <button class="menu-btn" onclick="toggleMenu()">≡</button>
        <div class="logo-text">@yield('logo-text', 'Rese')</div>
    </div>

    <div class="menu-overlay" id="menuOverlay" onclick="toggleMenu()"></div>
    <div class="side-menu" id="sideMenu">
        <button class="close-btn" onclick="toggleMenu()">×</button>
        <div class="menu-items">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="menu-item">Dashboard</a>
                    <a href="{{ route('admin.shop-owners.index') }}" class="menu-item">Shop Owners List</a>
                    <form method="POST" action="{{ route('logout') }}" class="menu-form">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>

                @elseif(auth()->user()->role === 'shop')
                    <a href="{{ route('shop.dashboard') }}" class="menu-item">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="menu-form">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>

                @else
                    <a href="{{ route('shops.index') }}" class="menu-item">Home</a>
                    <form method="POST" action="{{ route('logout') }}" class="menu-form">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                    <a href="{{ route('mypage') }}" class="menu-item">Mypage</a>
                @endif
            @else
                <a href="{{ route('shops.index') }}" class="menu-item">Home</a>
                <a href="{{ route('register') }}" class="menu-item">Registration</a>
                <a href="{{ route('login') }}" class="menu-item">Login</a>
            @endauth
        </div>
    </div>

    @yield('content')

    <script>
        function toggleMenu() {
            const overlay = document.getElementById('menuOverlay');
            const menu = document.getElementById('sideMenu');
            overlay.classList.toggle('active');
            menu.classList.toggle('active');
        }

        @yield('scripts')
    </script>
</body>
</html>
