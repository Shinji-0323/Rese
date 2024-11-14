@extends('admin.app')

@section('content')
    <h1>予約情報の確認</h1>

    <p>予約者名: {{ $reservation->user->name }}</p>
    <p>予約日時: {{ $reservation->date }} {{ $reservation->time }}</p>
    <p>店舗名: {{ $reservation->shop->name }}</p>

    <p>この予約は有効です。</p>
@endsection