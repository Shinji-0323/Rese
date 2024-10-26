@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('header')
    <div class="search">
        <form class="search__form" action="/search" method="get">
            <label class="search-label">
                <select class="search__select" name="region">
                    <option value="">All area</option>
                    @foreach ($shops->unique('region') as $shop)
                        <option class="select-box__option" value="{{ $shop->region }}" {{ request('region') == $shop->region ? 'selected' : '' }}>{{ $shop->region }} </option>
                    @endforeach
                </select>
            </label>

            <label class="search-label">
                <select class="search__select" name="genre">
                    <option value="">All genre</option>
                    @foreach ($shops->unique('genre') as $shop)
                        <option value="{{ $shop->genre }}" {{ request('genre') == $shop->genre ? 'selected' : '' }}>
                    {{ $shop->genre }}</option>
                    @endforeach
                </select>
            </label>

            <div class="search__item">
                <div class="search__item-button"></div>
                <label class="search__item-label">
                    <input type="text" name="name" class="search__item-input" placeholder="Search..." value="{{ request('name') }}" />
                </label>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="list">
        @foreach($shops as $shop)
        <div class="shop">
            <img class="shop__image" src="{{ $shop->image_url }}" alt="イメージ画像" >
            <div class="shop__content">
                <span class="shop__title">{{$shop['name']}}</span>
                <div class="shop__tag">
                    <p class="shop__tag-info">#{{$shop->region}}</p>
                    <P class="shop__tag-info">#{{$shop->genre}}</P>
                </div>
                <div class="shop__item">
                    <button class="shop__button" onclick="location.href='/detail/{{ $shop->id }}?from=index'">詳しく見る</button>
                    @if (Auth::check())
                        {{-- ユーザーがログインしている場合 --}}
                        <form class="shop__favorite" action="/favorite" method="post">
                        @csrf
                            <input type="hidden" name="shop_id" value="{{ $shop['id'] }}" />
                            @php
                                $favored = false;
                            @endphp
                            @foreach($favorites as $favorite)
                                @if($shop['id'] == $favorite['shop_id'])
                                    @php
                                        $favored = true;
                                        break;
                                    @endphp
                                @endif
                            @endforeach

                            @if($favored)
                                {{-- お気に入り登録済みなら赤色のハート --}}
                                <button class="heart__favorite"></button>
                            @else
                                {{-- お気に入り未登録ならグレーのハート --}}
                                <button class="heart"></button>
                            @endif
                        </form>
                    @else
                        {{-- ユーザーがログインしていない場合は全てグレー --}}
                        <button class="heart" onclick="location.href='/login'"></button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection