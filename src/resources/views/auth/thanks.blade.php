@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/thanks.css') }}">
@endsection

@section('content')
    <div class="content__wrap">
        <p class="content__text">
            会員登録ありがとうございます
        </p>
        <a class="content__button" href="/">ログインする</a>
    </div>
@endsection