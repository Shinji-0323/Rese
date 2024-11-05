@extends('admin.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/writer/shop_edit.css')}}">

@endsection

@section('content')
@if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="edit__wrap">
        <div class="edit__header">
            店舗情報の作成・更新
        </div>

        <div class="edit__content-wrap">
            <form action="/writer/shop-edit" method="post" enctype="multipart/form-data" class="edit__form">
                @csrf
                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        店舗名
                    </div>
                    <div class="edit__area">
                        <input type="text" name="name" class="edit__area-name" value="{{ $shop->name ?? '' }}" required>
                    </div>
                </div>

                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        エリア
                    </div>
                    <div class="edit__area">
                        <select name="region" class="edit__area-select" required>
                            <option value="" {{ $shop ? '' : 'selected' }} disabled>-- 選択 --</option>
                            @foreach ($shops->unique('region') as $shop)
                                <option class="select-box__option" value="{{ $shop->region }}" {{ request('region') == $shop->region ? 'selected' : '' }}>{{ $shop->region }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        ジャンル
                    </div>
                    <div class="edit__area">
                        <select name="genre" class="edit__area-select" required>
                            <option value="" {{ $shop ? '' : 'selected' }} disabled>-- 選択 --</option>
                            @foreach ($shops->unique('genre') as $shop)
                                <option value="{{ $shop->genre }}" {{ request('genre') == $shop->genre ? 'selected' : '' }}>
                            {{ $shop->genre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="edit__content textarea__content">
                    <div class="edit__title">
                        説明
                    </div>
                    <div class="edit__area textarea__area">
                        <textarea class="edit__area-textarea" name="outline" rows="10" required>{{ $shop->outline ?? '' }}</textarea>
                    </div>
                </div>

                <div class="edit__content input-file__content">
                    <div class="edit__title vertical-center">
                        イメージ
                    </div>
                    <div class="edit__area input-file__area">
                        @if ($shop == null)
                            <p class="edit__area-message">登録済みのイメージはありません。</p>
                        @else
                            <a href="{{ $shop->image_url }}" class="edit__area-link vertical-center">登録済みのイメージ</a>
                        @endif
                        <p class="edit__area-message">※変更する場合</p>
                        <input type="file" name="image_url" class="edit__area-file">
                    </div>
                </div>
                <div class="form__button">
                    <a href="/mypage" class="back__button">戻る</a>
                    <button type="submit" class="form__button-btn">{{ $shop ? '更新' : '登録' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection