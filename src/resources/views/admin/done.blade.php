@extends('admin.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/thanks.css') }}">
@endsection

@section('content')
    <div class="content__wrap">
        <p class="content__text">
            管理者の登録が完了しました。
        </p>
        <a class="content__button" href="{{ route('admin.user.index')}}">ログインする</a>
    </div>
@endsection