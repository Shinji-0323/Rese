@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection

@section('content')
<div>
    <div class="shop">
        <div class="shop__detail">
            <div class="shop__title">
                <button class="shop__title--button" onclick="location.href='{{ $_SERVER['HTTP_REFERER'] }}'"><</button>
                <p class="shop__title--name">{{$shop['store_name']}}</p>
            </div>
            <div class="shop__about">
                <img class="shop__about--img" src="{{ $shop->image_url }}" alt="イメージ画像" >
                <div class="shop__about--category">
                    <P>#{{$shop->region}}</P>
                    <p>#{{$shop->genre}}</p>
                </div>
                <P>{{$shop['summary']}}</P>
            </div>
        </div>
        <div class="shop__reservation">
            @if(!empty($today_date))
            <form class="shop__form" action="/reservation" method="post">
            @else
            <form class="shop__form" action="/update" method="post">
            @endif
                @csrf
                <div class="shop__reservation--input">
                    @if(!empty($today_date))
                    <p class="shop__reservation--title">予約</p>
                    @else
                    <p class="shop__reservation--title">予約変更</p>
                    @endif
                    @if (Auth::check())
                    <input type="hidden" name="user_id" value="{{Auth::id()}}" />
                    @endif
                    <input type="hidden" name="shop_id" value="{{$shop['id']}}" />
                    @error('date')
                    @foreach ($errors->get('date') as $error)
                    <p class="error__text">{{$error}}</p>
                    @endforeach
                    @enderror
                    @if(!empty($today_date))
                    <input class="shop__reservation--date" type="date" name="date" oninput="finalConfirmation()" id="date" value="{{$today_date}}"/>
                    @else
                    <input class="shop__reservation--date" type="date" name="date" value="{{$reservation['date']}}"/>
                    @endif
                    @error('time')
                    @foreach ($errors->get('time') as $error)
                    <p class="error__text">{{$error}}</p>
                    @endforeach
                    @enderror
                    <input class="shop__reservation--time" type="time" name="time" oninput="finalConfirmation()" id="time" min="09:00" max="18:00" />
                    @error('number')
                    @foreach ($errors->get('number') as $error)
                    <p class="error__text">{{$error}}</p>
                    @endforeach
                    @enderror
                    <select class="shop__reservation--number" name="number" oninput="finalConfirmation()" id="number" >
                        <option value="1">1人</option>
                        <option value="2">2人</option>
                        <option value="3">3人</option>
                        <option value="4">4人</option>
                        <option value="5">5人</option>
                    </select>
                </div>
                <div class="shop__confirmation">
                    <div class="shop__confirmation--up">
                        <p>Shop</p>
                        <p>{{$shop['store_name']}}</p>
                    </div>
                    <div class="shop__confirmation--item">
                        <p>Date</p>
                        <p id="date">日にち</p>
                    </div>
                    <div class="shop__confirmation--item">
                        <p>Time</p>
                        <p id="time">時間</p>
                    </div>
                    <div class="shop__confirmation--down">
                        <p>Number</p>
                        <p id="number">人数</p>
                    </div>
                </div>
                <div class="shop__submit">
                    @if(!empty($today_date))
                    <input class="shop__submit--button" type="submit" value="予約する" />
                    @else
                    <input type="hidden" name="id" value="{{$reservation->id}}" />
                    <input class="shop__submit--button" type="submit" value="予約変更" />
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection