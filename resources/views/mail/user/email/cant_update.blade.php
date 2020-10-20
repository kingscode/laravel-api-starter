@php
    // @formatter:off

    /** @var \App\Models\User $user */
@endphp

@component('mail::message')
# Dear {{ $user->getName() }},

You are receiving this email because you've requested to change your email address.

Sadly, you already have an account with this email address in place and we are unable change it.

Yours,<br>
{{ config('app.name') }}
@endcomponent
