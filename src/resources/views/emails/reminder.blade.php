@component('mail::message')
#  予約リマインダー

{{ $user->name }} 様

以下の内容で予約が入っております。

- 予約日時: {{ $reservation->date }} {{ $reservation->time }}
- 店 舗 名: {{ $reservation->shop->name }}
- 人    数: {{ $reservation->number }}

当日こちらのQRコードを提示してください。

<img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">

ご来店をお待ちしております。

Thanks,
{{ config('app.name') }}
@endcomponent