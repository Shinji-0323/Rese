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
                    @foreach ($regions as $region)
                    <option class="select-box__option" value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }} </option>
                    @endforeach
                </select>
            </label>

            <label class="search-label">
                <select class="search__select" name="genre">
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
                    <input type="text" name="store_name" class="search__item-input" placeholder="Search..." />
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
                <p class="shop__title">{{$shop['name']}}</p>
                <div class="shop__tag">
                    <p>#{{$shop->region}}</p>
                    <P>#{{$shop->genre}}</P>
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
                                <button class="heart__favorite heart__red"></button>
                            @else
                                {{-- お気に入り未登録ならグレーのハート --}}
                                <button class="heart__favorite heart__gray"></button>
                            @endif
                        </form>
                    @else
                        {{-- ユーザーがログインしていない場合は全てグレー --}}
                        <button class="heart__favorite heart__gray" onclick="location.href='/login'"></button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection