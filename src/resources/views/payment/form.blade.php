@extends('admin.app')

@section('js')
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* カード情報のスタイル */
        #card-element {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
@endsection

@section('content')
<h1>お会計</h1>

@if (session('success_message'))
    <p style="color: green;">{{ session('success_message') }}</p>
@endif

@if ($errors->any())
    <p style="color: red;">{{ $errors->first() }}</p>
@endif

<form action="{{ route('payment.process') }}" method="POST" id="payment-form">
    @csrf
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <label for="amount">支払金額 (円):</label>
    <input type="number" name="amount" id="amount" required>

    <div id="card-element"><!-- カード情報が入力されます --></div>
    <button id="card-button" type="submit">支払う</button>
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