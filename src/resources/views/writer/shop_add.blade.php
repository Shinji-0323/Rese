@extends('admin.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/writer/shop.css')}}">

@endsection

@section('content')
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="shop__wrap">
        <div class="shop__header">
            店舗情報の作成
        </div>

        <div class="shop__content-wrap">
            <form action="{{ route('shop-add.create') }}" method="post" enctype="multipart/form-data" class="shop__form">
                @csrf
                <div class="shop__content">
                    <div class="shop__title vertical-center">
                        店舗名
                    </div>
                    <div class="shop__area">
                        <input type="text" name="name" class="shop__area-name" value="{{ old('name') }}">
                    </div>
                    <div class="content__error">
                        @error('name')
                            ※{{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="shop__content">
                    <div class="shop__title vertical-center">
                        エリア
                    </div>
                    <div class="shop__area">
                        <select name="region" class="shop__area-select">
                            <option value="" selected disabled>-- 選択 --</option>
                            @foreach ($shops->unique('region') as $shopRegion)
                                <option class="select-box__option" value="{{ $shopRegion->region }}" >{{ $shopRegion->region }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="content__error">
                        @error('region')
                            ※{{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="shop__content">
                    <div class="shop__title vertical-center">
                        ジャンル
                    </div>
                    <div class="shop__area">
                        <select name="genre" class="shop__area-select">
                            <option value="" selected disabled>-- 選択 --</option>
                            @foreach ($shops->unique('genre') as $shopGenre)
                                <option value="{{ $shopGenre->genre }}" >
                            {{ $shopGenre->genre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="content__error">
                        @error('genre')
                            ※{{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="shop__content textarea__content">
                    <div class="shop__title">
                        説明
                    </div>
                    <div class="shop__area textarea__area">
                        <textarea class="shop__area-textarea" name="description" rows="10"></textarea>
                    </div>
                    <div class="content__error">
                        @error('description')
                            ※{{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="shop__content input-file__content">
                    <div class="shop__title vertical-center">
                        イメージ
                    </div>
                    <div class="shop__area input-file__area">
                        <input type="file" name="image_url" class="shop__area-file">
                    </div>
                    <div class="content__error">
                        @error('image_url')
                            ※{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__button">
                    <a href="
                        @if(Auth::user()->role === 'admin')
                            {{ route('admin.user.index') }}
                        @elseif(Auth::user()->role === 'store_manager')
                            {{ route('confirm-shop-reservation') }}
                        @endif
                    " class="back__button">戻る</a>
                    <button type="submit" class="form__button-btn">登録</button>
                </div>
            </form>
        </div>
    </div>
@endsection