@php
    // @formatter:off
@endphp

@component('mail::message')
# Dear,

You are receiving this email because you've tried to create an account on [{{ config('app.name') }}]({{ $front_end_url }})<br>
Please click on the button below to verify your account.

@component('mail::button', ['url' => $verify_url])
    Verify
@endcomponent

Yours,<br>
{{ config('app.name') }}

@slot('subcopy')
If you’re having trouble clicking the "Request new password" button, copy and paste the URL below<br>
into your web browser: [{{ $verify_url }}]({{ $verify_url }})
@endslot
@endcomponent
