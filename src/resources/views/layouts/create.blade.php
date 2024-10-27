@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css')}}">

@endsection

@section('content')
    <div class="content__wrap">
        <p class="content__title">店舗登録</p>
        <form class="shop__content" action="/store" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="shop__content__title">
                <label for="name">店舗名</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="shop__content__title">
                <label for="name">エリア</label>
                <input type="text" name="region" id="region" required>
            </div>
            <div class="shop__content__title">
                <label for="name">ジャンル</label>
                <input type="text" name="genre" id="genre" required>
            </div>
            <div class="shop__content__title textarea">
                <label for="name">説明</label>
                <input type="textarea" name="description" id="description"  placeholder="コメントを入力">
            </div>
            <div class="shop__content__title">
                <label for="image">店舗の写真</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>

            <button class="content__button" type="submit">店舗を登録</button>
        </form>
    </div>
@endsection