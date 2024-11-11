@component('mail::message')
#  Reseからのお知らせ

{{ $user->name }} 様
{{ $messageContent }}

Thanks,{{ config('app.name') }}
@endcomponent