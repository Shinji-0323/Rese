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
                <p class="shop__title">{{$shop['store_name']}}</p>
                <div class="shop__tag">
                    <p>#{{$shop->region}}</p>
                    <P>#{{$shop->genre}}</P>
                </div>
                <div class="shop__item">
                    <button class="shop__button" onclick="location.href='/detail/{{ $shop->id }}?from=index'">詳しく見る</button>
                    <form class="shop__favorite" action="/favorite" method="get">
                    @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop['id'] }}" />
                        <input type="hidden" name="page" value="index" />
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
                        <button class="heart__favorite"></button>
                    @else
                        <button class="heart"></button>
                    @endif
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection