@extends('layouts.app')

@section('content')
    <h1>予約QRコード</h1>
    <p>以下のQRコードを来店時にご提示ください。</p>

    <div>
        {!! $qrCode !!}
    </div>

    <p>予約日時: {{ $reservation->date }} {{ $reservation->time }}</p>
    <p>店舗名: {{ $reservation->shop->name }}</p>
@endsection