@php
    // @formatter:off

    /** @var \App\Models\User $user */
@endphp

@component('mail::message')
# Dear {{ $user->getName() }},

Forgotten your password? Don't worry! It happens to the best of us.

@component('mail::button', ['url' => $password_reset_url])
    Reset password
@endcomponent

This email is valid for 15 minutes.

Yours,<br>
{{ config('app.name') }}

@slot('subcopy')
    If you’re having trouble clicking the "Activate" button, copy and paste the URL below into your web browser:
    [{{ $password_reset_url }}]({{ $password_reset_url }})
@endslot
@endcomponent
