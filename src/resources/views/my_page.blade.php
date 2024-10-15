@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_page.css')}}">
@endsection

@section('content')

<div class="my_page">
    <div class="reservation">
        <p class="reservation__situation">予約状況</p>
        @foreach($reservations as $order => $reservation)
        <div class="reservation__card">
            <div class="reservation__title">
                <div class="reservation__title-mark">時</div>
                <p class="reservation__title-number">予約{{++$order}}</p>
                <form class="reservation__title-delete" action="/delete_reservation" method="get">
                @csrf
                    <input type="hidden" name="reservation_id" value="{{$reservation->id}}" />
                    <button class="">✕</button>
                </form>
            </div>
            <div class="reservation__contents">
                <table class="reservation__contents-table">
                    <tr>
                        <th>Shop</th>
                        <td>{{$reservation->shop['name']}}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{$reservation['date']}}</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>{{$reservation['time']}}</td>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <td>{{$reservation['number']}}人</td>
                    </tr>
                </table>
                <form class="reservation__contents-change"action="/reservation_change" method="get">
                @csrf
                    <input type="hidden" name="shop_id" value="{{$reservation['shop_id']}}" />
                    <input type="hidden" name="user_id" value="{{$reservation['user_id']}}" />
                    <button class="">予約変更</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="favorite">
        <p class="favorite__name">{{Auth::user()->name}}さん</p>
        <p class="favorite__shop">お気に入り店舗</p>
        <div class="favorite__list">
            @foreach($favorites as $favorite)
            <div class="shop">
                <img class="shop__image" src="{{asset($favorite->shop['image_path'])}}"> >
                <div class="shop__content">
                    <p class="shop__title">{{$favorite->shop['name']}}</p>
                    <div class="shop__tag">
                        <p>#{{$favorite['region']}}</p>
                        <P>#{{$favorite['genre']}}</P>
                    </div>
                    <div class="shop__item">
                        <button class="shop__button" onclick="location.href='/detail/{shop_id}'">詳しく見る</button>
                        <form class="shop__favorite" action="/favorite" method="get">
                        @csrf
                            <input type="hidden" name="shop_id" value="{{ $favorite->shop['id'] }}" />
                            <input type="hidden" name="page" value="my_page" />
                            <button class="heart__favorite"></button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection