@php
    // @formatter:off
@endphp

@component('mail::message')
# Dear,

Somebody has tried to create a new account but you already have one.
If you have forgotten your password then you can request a new one.

@component('mail::button', ['url' => $password_forgotten_url])
    Request new password
@endcomponent

Yours,<br>
{{ config('app.name') }}

@slot('subcopy')
If you’re having trouble clicking the "Request new password" button, copy and paste the URL below<br>
into your web browser: [{{ $password_forgotten_url }}]({{ $password_forgotten_url }})
@endslot
@endcomponent
