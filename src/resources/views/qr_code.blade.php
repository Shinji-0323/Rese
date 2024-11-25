@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/qr_code.css')}}">
@endsection

@section('content')
    <div class="content__wrap">
        <div class="content__title">
            <p class="heder__title">予約QRコード</p>
        </div>
        <div class="content__text">
            <p class="heder__text">以下のQRコードを来店時にご提示ください。</p>
        </div>

        <div>
            {!! $qrCode !!}
        </div>

        <div class="content__detail">
            <div class="detail__title">
                <p class="reservation__title">予約日時: {{ $reservation->date }} {{ $reservation->time }}</p>
            </div>
            <div class="detail__text">
                <p class="reservation__text">店舗名: {{ $reservation->shop->name }}</p>
            </div>
        </div>
        <a class="content__button" href="/">戻る</a>
    </div>
@endsection