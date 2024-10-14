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
</head>

<body>
    <header>
        <div class="header__main">
            <a class="icon" href="#modal">
                <div class="icon__mark">
                    <div class="icon__border"></div>
                </div>
                <span class="icon__title">Rese</span>
            </a>
            @yield('search')
        </div>
    </header>
    <main>
        @yield('content')
        <div class="modal" id="modal">
        @if(Auth::check())
            <div class="modal__close">
                <a href="#" class="modal__close-btn"></a>
            </div>
            <div class="modal__inner" >
                <ul class="modal__content">
                    <li class="modal__content-list">
                        <a href="{{ route('index')}}">Home</a>
                    </li>
                    <li class="modal__content-list">
                        <form class="modal__content-log" action="/logout" method="post">
                            @csrf
                            <button class="model__content-list__auth">Logout</button>
                        </form>
                    </li>
                    <li class="modal__content-list">
                        <a href="{{ route('my_page')}}">Mypage</a>
                    </li>
                </ul>
            </div>
            @else
            <div class="modal__close">
                <a href="#" class="modal__close-btn"></a>
            </div>
            <div class="modal__inner" >
                <ul class="modal__content">
                    <li class="modal__content-list">
                        <a href="{{ route('index')}}">Home</a>
                    </li>
                    <li class="modal__content-list">
                        <form class="modal__content-log"  action="/register" method="get">
                            @csrf
                            <button class="model__content-list__auth">Registration</button>
                        </form>
                    </li>
                    <li class="modal__content-list">
                        <form class="modal__content-login" action="/login" method="get">
                            @csrf
                            <button class="model__content-list__auth">Login</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </main>
</body>

</html>