@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
    <div class="detail__wrap">
        <div class="detail__header">
            <div class="header__title">
                <a href="/" class="header__back"><</a>
                <span class="header__shop-name">{{ $shop->name }}</span>
            </div>
        </div>
        <div class="detail__image">
            <img src="{{ $shop->image_url }}" alt="イメージ画像" class="detail__image-img">
        </div>
        <div class="detail__tag">
            <p class="detail__tag-info">#{{ $shop->region }}</p>
            <p class="detail__tag-info">#{{ $shop->genre }}</p>
        </div>
        <div class="detail__outline">
            <p class="detail__outline-text">{{ $shop->description }}</p>
        </div>
    </div>

    <form class="reservation__wrap" action="{{ isset($reservation) ? url('/reserve/' . $reservation->id . '/edit') : url('/reserve') }}"
        method="post">
        @csrf
        <div class="reservation__content">
            <p class="reservation__title">{{ request()->is('*edit*') ? '予約変更' : '予約' }}
            </p>
            <div class="form__content">
                <input type="date" id="datePicker" class="form__item" name="date"
                    value="{{ request()->is('*edit*') ? $reservation->date : '' }}">
                <script>
                    window.onload = function() {
                        var today = new Date().toISOString().split('T')[0];
                        document.getElementById("datePicker").setAttribute('min', today);
                    };
                </script>
                <div class="error__item">
                    @error('date')
                        <span class="error__message">{{ $message }}</span>
                    @enderror
                </div>
                <select name="time" class="form__item">
                    <option value="" {{ request()->is('*edit*') && isset($reservation->time) ? '' : 'selected' }}
                        disabled>-- 時間を選択してください --</option>
                    @foreach (['11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30'] as $time)
                        <option value="{{ $time }}"
                            {{ request()->is('*edit*') && $time == date('H:i', strtotime($reservation->time)) ? 'selected' : '' }}>
                            {{ $time }}
                        </option>
                    @endforeach
                </select>
                <div class="error__item">
                    @error('time')
                        <span class="error__message">{{ $message }}</span>
                    @enderror
                </div>
                <select name="number" class="form__item">
                    <option value="" {{ request()->is('*edit*') && isset($reservation->time) ? '' : 'selected' }}
                        disabled>--人数を選択してください --</option>
                    @foreach (range(1, 10) as $number)
                        <option value="{{ $number }}"
                            {{ request()->is('*edit*') && $number == $reservation->number ? 'selected' : '' }}>
                            {{ $number }}人
                        </option>
                    @endforeach
                </select>
                <div class="error__item">
                    @error('number')
                        <span class="error__message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="reservation__group">
                <div class="reservation__area">
                    <table class="reservation__table">
                        <tr>
                            <th class="table__header">Shop</th>
                            <td class="table__item">{{ $shop->name }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Date</th>
                            <td class="table__item" id="dateId">{{ request()->is('*edit*') ? $reservation->date : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="table__header">Time</th>
                            <td class="table__item" id="timeId">
                                {{ request()->is('*edit*') ? date('H:i', strtotime($reservation->time)) : '' }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Number</th>
                            <td class="table__item" id="numberId">
                                {{ request()->is('*edit*') ? $reservation->number . '人' : '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="reservation__button">
            @if (Auth::check())
                    <button type="submit" class="reservation__button-btn"
                        onclick="return confirmReservation()">{{ request()->is('*edit*') ? '予約内容を変更する' : '予約する' }}</button>
            @else
                <button type="button" class="reservation__button-btn--disabled" disabled>予約は<a href="/register"
                        class="reservation__button-link">会員登録</a><a href="/login"
                        class="reservation__button-link">ログイン</a>が必要です</button>
            @endif
        </div>
    </form>
@endsection