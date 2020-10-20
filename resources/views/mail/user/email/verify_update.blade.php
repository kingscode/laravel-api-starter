@php
    // @formatter:off

    /** @var \App\Models\User $user */
@endphp

@component('mail::message')
# Dear {{ $user->getName() }},

You are receiving this email because you've requested to change your email address.<br>
To verify this update click on the button below.

@component('mail::button', ['url' => $verify_url])
    Verify email
@endcomponent

Yours,<br>
{{ config('app.name') }}

@slot('subcopy')
    If you’re having trouble clicking the "Activate" button, copy and paste the URL below into your web browser:
    [{{ $verify_url }}]({{ $verify_url }})
@endslot
@endcomponent
