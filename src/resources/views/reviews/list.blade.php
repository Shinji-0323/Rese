@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/review/review_list.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/review_list.js') }}"></script>
@endsection

@section('content')
    <div class="review__wrap">
        <div class="review__header">
            レビュー
        </div>

        <div class="review__content-wrap">
            <div class="review__content shop__data">
                <div class="review__title shop-image__wrap">
                    <img class="shop__image" src="{{ $shop['image_url'] }}" alt="イメージ写真">
                </div>
                <div class="review__area review__detail">
                    <p class="shop__name">{{ $shop->name }}</p>
                    <span class="rating__name">総合：</span>
                    <span class="rating__star" data-rate="{{ number_format($avgStar,1) }}">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star">★</span>
                        @endfor
                    </span>
                    <span class="rating__number">{{ number_format($avgStar,1) }}</span>
                    <p class="shop__detail">{{ $shop->outline }}</p>
                </div>
            </div>

            @foreach ($shopReviews as $shopReview)
                <div class="review__container">
                    <div class="review__content">
                        <div class="review__title review__title--vertical-center">
                            評価 :
                        </div>
                        <div class="review__area">
                            <span class="rating__star" data-rate="{{ number_format($shopReview->star,1) }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $shopReview->star ? 'star--filled' : 'star--empty' }}">★</span>
                                @endfor
                            </span>
                            <span class="rating__number">{{ number_format($shopReview->star,1) }}</span>
                        </div>
                    </div>

                    <div class="review__content">
                        <div class="review__title">
                            本文 :
                        </div>
                        <div class="review__area">
                            <p class="review__comment">{{ $shopReview->comment }}</p>
                        </div>
                    </div>

                    @if ($shopReview->image_url)
                        <div class="review__image-area">
                            <a href="{{ $shopReview->image_url }}">
                                <img src="{{ $shopReview->image_url }}" alt="レビュー画像" class="review__image">
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection