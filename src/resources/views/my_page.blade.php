@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_page.css')}}">
@endsection
@section('css')
<script src="https://kit.fontawesome.com/706e1a4697.js" crossorigin="anonymous"></script>
@endsection

@section('content')
    <p class="user__name">{{ Auth::user()->name }}さん</p>
    <div class="mypage__wrap">
        <div class="reservation__wrap">
            <div class="reservation__tab">
                <p class="reservation__title">予約状況</p>
                @foreach($reservations as $order => $reservation)
                <div class="reservation__content">
                    <div class="reservation__header">
                        <div class="header__mark"><i class="fa-regular fa-clock"></i></div>
                        <p class="header__number">予約{{++$order}}</p>
                        <form class="header__form" action="{{ route('reservation.edit', ['id' => $reservation->id]) }}" method="get">
                            <input type="hidden" name="shop_id" value="{{$reservation['shop_id']}}" />
                            <input type="hidden" name="user_id" value="{{$reservation['user_id']}}" />
                            <button class="">予約変更</button>
                        </form>
                        <a class="header__form" href="{{ route('qr_code', ['id' => $reservation->id]) }}">
                            <button class="">QRコード</button>
                        </a>
                        <form class="header__form" action="/reservation/delete" method="post">
                            @csrf
                            <input type="hidden" name="reservation_id" value="{{$reservation->id}}" />
                            <button class="header__button"><i class="fa-regular fa-circle-xmark"></i></button>
                        </form>
                    </div>
                    <div class="reservation__detail">
                        <table class="reservation__table">
                            <tr>
                                <th class="table__header">Shop</th>
                                <td class="table__item">{{$reservation->shop['name']}}</td>
                            </tr>
                            <tr>
                                <th class="table__header">Date</th>
                                <td class="table__item">{{$reservation['date']}}</td>
                            </tr>
                            <tr>
                                <th class="table__header">Time</th>
                                <td class="table__item">{{\Carbon\Carbon::createFromFormat('H:i:s', $reservation['time'])->format('H:i')}}</td>
                            </tr>
                            <tr>
                                <th class="table__header">Number</th>
                                <td class="table__item">{{$reservation['number']}}人</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="reservation__tab">
                <p class="reservation__title">予約履歴</p>
                @foreach ($histories as $reservation)
                    <div class="reservation__content reservation__content--steelblue">
                        <div class="reservation__header">
                            <p class="header__number">履歴{{ $loop->iteration }}</p>
                            <div class="header__form">
                                <a href="{{ route('review', ['shop_id' => $reservation->shop_id]) }}"><button>{{ request()->is('*edit*') ? 'レビュー変更' : 'レビュー投稿' }}</button></a>
                            </div>
                            <a class="header__form" href="{{ route('qr_code', ['id' => $reservation->id]) }}">
                                <button class="">QRコード</button>
                            </a>
                        </div>
                        <table class="reservation__table">
                            <tr>
                                <th class="table__header">Shop</th>
                                <td class="table__item">{{ $reservation->shop['name'] }}</td>
                            </tr>
                            <tr>
                                <th class="table__header">Date</th>
                                <td class="table__item">{{ $reservation['date'] }}</td>
                            </tr>
                            <tr>
                                <th class="table__header">Time</th>
                                <td class="table__item">{{ date('H:i',strtotime($reservation['time'])) }}</td>
                            </tr>
                            <tr>
                                <th class="table__header">Number</th>
                                <td class="table__item">{{ $reservation['number'] }}人</td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="favorite__wrap">
            <p class="favorite__title">お気に入り店舗</p>
                <div class="shop__wrap">
                    @foreach($favorites as $favorite)
                        <div class="shop__content">
                            <img class="shop__image" src="{{ $favorite->shop['image_url'] }}" alt="イメージ画像">
                            <div class="shop__item">
                                <p class="shop__title">{{$favorite->shop['name']}}</p>
                                <div class="shop__tag">
                                    <p class="shop__tag-info">#{{$favorite->shop['region']}}</p>
                                    <p class="shop__tag-info">#{{$favorite->shop['genre']}}</p>
                                </div>
                                <div class="shop__button">
                                    <button class="shop__button-detail" onclick="location.href='/detail/{{$favorite->shop['id']}}'">詳しく見る</button>
                                    <form class="shop__favorite" action="/favorite" method="post">
                                    @csrf
                                        <input type="hidden" name="shop_id" value="{{ $favorite->shop['id'] }}" />
                                        <button class="heart__favorite"></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection