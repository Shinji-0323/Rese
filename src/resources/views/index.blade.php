@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="https://kit.fontawesome.com/706e1a4697.js" crossorigin="anonymous"></script>
@endsection

@section('header')
    <form class="header__right" action="/" method="get">
        <div class="header__search">
            <label class="select-box__label">
                <select name="region" class="select-box__item">
                    <option value="">All area</option>
                    @foreach ($regions as $region)
                        <option class="select-box__option" value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="select-box__label">
                <select name="genre" class="select-box__item">
                    <option value="">All genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                            {{ $genre }}</option>
                    @endforeach
                </select>
            </label>

            <div class="search__item">
                <div class="search__item-button"></div>
                <label class="search__item-label">
                    <input type="text" name="word" class="search__item-input" placeholder="Search ..." value="{{ request('word') }}">
                </label>
            </div>
        </div>
    </form>
@endsection

@section('content')
    <div class="shop__wrap">
        @foreach ($shops as $shop)
            <div class="shop__content">
                <img class="shop__image" src="{{ $shop->image_url }}" alt="イメージ画像">
                <div class="shop__item">
                    <span class="shop__title">{{ $shop->name }}</span>
                    <div class="shop__tag">
                        <p class="shop__tag-info">#{{ $shop->region }}</p>
                        <p class="shop__tag-info">#{{ $shop->genre }}</p>
                    </div>
                    <div class="shop__button">
                        <a href="/detail/{{ $shop->id }}" class="shop__button-detail">詳しくみる</a>
                        @if (Auth::check())
                            @if (in_array($shop->id, $favorites))
                                <form class="shop__button-favorite form" action="/favorite" method="post">
                                    @csrf
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <button type="submit" class="shop__button-favorite-btn" title="お気に入り削除">
                                        <i class="fa-sharp-duotone fa-solid fa-heart fa-2x"></i>
                                    </button>
                                </form>
                            @else
                                <form class="shop__button-favorite form" action="/favorite" method="post">
                                    @csrf
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <button type="submit" class="shop__button-favorite-btn" title="お気に入り追加">
                                        <i class="fa-sharp fa-solid fa-heart fa-2x"></i>
                                    </button>
                                </form>
                            @endif
                        @else
                            <button type="button" onclick="location.href='/login'" class="shop__button-favorite-btn">
                                <i class="fa-sharp-duotone fa-solid fa-heart fa-2x"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection