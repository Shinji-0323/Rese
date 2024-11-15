<!DOCTYPEhtml>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
    @yield('js')
</head>

<body>
    <header>
        <div class="header__left">
            <div class="header__icon">
                <input id="drawer__input" class="drawer__hidden" type="checkbox">
                <label for="drawer__input" class="drawer__open"><span></span></label>
                <nav class="nav__content">
                    <ul class="nav__list">
                        @if(Auth::check())
                            @if(Auth::user()->role === 'admin')
                                <li class="nav__item"><a class="nav__item-link" href="{{ route('admin.user.index')}}">管理者一覧</a></li>
                            @endif
                            <li class="nav__item"><a class="nav__item-link" href="{{ route('shop-add')}}">店舗情報登録</a></li>
                            @if(Auth::user()->role === 'store_manager')
                                <li class="nav__item"><a class="nav__item-link" href="{{ route('shop-edit')}}">店舗情報更新</a></li>
                                <li class="nav__item"><a class="nav__item-link" href="{{ route('confirm-shop-reservation')}}">予約情報</a></li>
                            @endif
                            <li class="nav__item">
                                <form class="nav__item-link" action="{{ route('admin.logout')}}" method="post">
                                    @csrf
                                    <button class="nav__item-button">Logout</button>
                                </form>
                            </li>
                        @else
                            <li class="nav__item"><a class="nav__item-link" href="/admin/register">Registration</a></li>
                            <li class="nav__item"><a class="nav__item-link" href="/admin/login">Login</a></li>
                            <li class="nav__item"><a class="nav__item-link" href="/admin/payment">お会計</a></li>
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