@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth/verified.css') }}">

@section('content')
    <div class="header__wrap">
        <div class="header__text">
            {{ __('メールアドレスをご確認ください。') }}
        </div>
    </div>
    <div class="body__wrap">
        @if (session('resent'))
            <div class="alert-success" role="alert">
                {{ __('ご登録いただいたメールアドレスに確認用のリンクをお送りしました。') }}
            </div>
        @endif

        <p class="body__text">
            {{ __('メールをご確認ください。') }}
        </p>
        <p class="body__text">
            {{ __('もし確認用メールが送信されていない場合は、下記をクリックしてください。') }}
        </p>
        <form class="form__item form__item-button" method="POST" action="{{ route('admin.verification.send') }}">
            @csrf
            <button type="submit" class="form__input form__input-button">
                {{ __('確認メールを再送信する') }}
            </button>
        </form>

        <a class="back__button" href="{{ route('admin.logout') }}">戻る</a>
    </div>
@endsection