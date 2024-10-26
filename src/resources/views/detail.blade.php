@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection

@section('content')
    <div class="detail__wrap">
        <div class="detail__header">
            <div class="header__title">
                <a href="{{ $backRoute }}" class="header__back"><</a>
                <span class="header__shop-name">{{$shop->name}}</span>
            </div>
        </div>
        <div class="detail__image">
            <img class="detail__image-img" src="{{ $shop->image_url }}" alt="イメージ画像" >
        </div>
        <div class="detail__tag">
            <P class="detail__tag-info">#{{$shop->region}}</P>
            <p class="detail__tag-info">#{{$shop->genre}}</p>
        </div>
        <div class="detail__outline">
            <P class="detail__outline-text">{{$shop->description}}</P>
        </div>
        <a href="/review/shop/{{ $shop->id }}" class="all-review__button">全ての口コミ情報</a>
    </div>

    @if(!empty($today_date))
        <form class="reservation__wrap" action="/reservation" method="post">
    @else
        <form class="reservation__wrap" action="{{ route('reservation.update', ['id' => $reservation->id]) }}" method="post">
    @endif
        @csrf
        <div class="reservation__content">
            <p class="reservation__title">{{ request()->is('*edit*') ? '予約変更' : '予約' }}</p>
            @if (Auth::check())
                <input type="hidden" name="user_id" value="{{Auth::id()}}" />
            @endif
                <input type="hidden" name="shop_id" value="{{$shop['id']}}" />
            @error('date')
                @foreach ($errors->get('date') as $error)
                <p class="error__text">{{$error}}</p>
                @endforeach
            @enderror
            <div class="form__content">
                @if(!empty($today_date))
                    <input class="form__item" type="date" name="date" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" value="{{ $today_date ?? $reservation['date'] }}" onchange="updateDetails()">
                @else
                    <input class="form__item" type="date" name="date" value="{{$reservation['date']}}" onchange="updateDetails()"/>
                @endif
                @error('time')
                    @foreach ($errors->get('time') as $error)
                    <p class="error__text">{{$error}}</p>
                    @endforeach
                @enderror
                <input class="form__item" type="time" name="time" onchange="updateDetails()" min="09:00" max="22:00" />
                @error('number')
                    @foreach ($errors->get('number') as $error)
                    <p class="error__text">{{$error}}</p>
                    @endforeach
                @enderror
                <select class="form__item" name="number" onchange="updateDetails()" >
                    <option value="" {{ request()->is('*edit*') && isset($reservation->time) ? '' : 'selected' }}
                    disabled>--人数を選択してください --</option>
                        @foreach (range(1, 5) as $number)
                        <option value="{{ $number }}"
                        {{ request()->is('*edit*') && $number == $reservation->number ? 'selected' : '' }}>
                        {{ $number }}人
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="reservation__group">
            <div class="reservation__area">
                <table class ="reservation__table">
                    <tr>
                        <th class="table__header">Shop</th>
                        <td class="table__item" id="shop-name">{{$shop['name']}}</td>
                    </tr>
                    <tr>
                        <th class="table__header">Date</th>
                        <td class="table__item" id="selected-date">{{ request()->is('*edit*') ? $reservation->date : '' }}</td>
                    </tr>
                    <tr>
                        <th class="table__header">Time</th>
                        <td class="table__item" id="selected-time">{{ request()->is('*edit*') ? date('H:i', strtotime($reservation->time)) : '' }}</td>
                    </tr>
                    <tr?>
                        <th class="table__header">Number</th>
                        <td class="table__item" id="selected-number">{{ request()->is('*edit*') ? $reservation->number . '人' : '' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="reservation__button">
            @if (Auth::check())
                @if(!empty($today_date))
                    <input class="reservation__button-btn" type="submit" value="予約する" />
                @else
                    <input type="hidden" name="id" value="{{$reservation->id}}" />
                    <input class="reservation__button-btn" type="submit" value="予約変更" />
                @endif
            @else
                <button type="button" class="reservation__button-btn--disabled" disabled>予約は
                    <a href="/register"
                        class="reservation__button-link">会員登録</a>
                    <a href="/login"
                        class="reservation__button-link">ログイン</a>が必要です
                </button>
            @endif
        </div>
    </form>

    <script>
        function updateDetails() {
            // 日付の更新
            var selectedDate = document.querySelector('input[name="date"]').value;
            document.getElementById('selected-date').textContent = selectedDate;

            // 時間の更新
            var selectedTime = document.querySelector('input[name="time"]').value;
            document.getElementById('selected-time').textContent = selectedTime;

            // 人数の更新
            var selectedNumber = document.querySelector('select[name="number"]').value;
            document.getElementById('selected-number').textContent = selectedNumber + "人";
        }
    </script>
@endsection