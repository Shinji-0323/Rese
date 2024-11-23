@extends('admin.app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/payment/form.css')}}">
@endsection
@section('js')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="header__wrap">
        <div class="header__title">
            お会計
        </div>
        <div class="header__text">
            @if (session('success_message'))
                <p>{{ session('success_message') }}</p>
            @endif
        </div>
        <div class="error__message">
            @if ($errors->any())
                <p>{{ $errors->first() }}</p>
            @endif
        </div>
    </div>

    <form class="card__wrap" action="{{ route('payment.process') }}" method="POST" id="payment-form">
        @csrf
        <div class="card__content">
            <div class="card__text">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label for="amount" class="card__text__label">支払金額 (円):</label>
                <input class="card__text__label" type="number" name="amount" id="amount" required>
            </div>
            <div class="card__element" id="card-element"><!-- カード情報が入力されます --></div>
        </div>
        <button class="card__button" id="card-button" type="submit">支払う</button>
    </form>

    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card', {
                hidePostalCode: true // 郵便番号入力欄を非表示に設定
            });
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const {token, error} = await stripe.createToken(cardElement);
                if (error) {
                // トークン生成エラーを表示
                alert(error.message);
            } else {
                // トークンが生成された場合、hidden inputにセットして送信
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // フォームを送信
                form.submit();
            }
        });
    </script>
@endsection