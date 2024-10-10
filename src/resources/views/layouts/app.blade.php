<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header>
        <div class="header__left">
            <div class="header__icon">
                <input id="drawer__input" class="drawer__hidden" type="checkbox">
                <label for="drawer__input" class="drawer__open"><span></span></label>
                <nav class="nav__content">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a class="nav__item-link" href="/">Home</a>
                        </li>
                        @if (Auth::check())
                            <li class="nav__item">
                                <form action="/logout" method="post">
                                    @csrf
                                    <button class="nav__item-link" type="submit">Logout</button>
                                </from>
                            </li>
                            <li class="nav__item">
                                <a class="nav__item-link" href="/my_page">Mypage</a></li>
                        @else
                            <li class="nav__item">
                                <a class="nav__item-link" href="/register">Registration</a>
                            </li>
                            <li class="nav__item">
                                <a class="nav__item-link" href="/login">Login</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="header__logo">Rese</div>
        </div>
        @yield('header')
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>