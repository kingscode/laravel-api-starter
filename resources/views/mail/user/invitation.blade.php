@php
    // @formatter:off

    /** @var \App\Models\User $user */
@endphp

@component('mail::message')
# Dear {{ $user->getName() }},

Activate your account via the button below.

@component('mail::button', ['url' => $acceptation_url])
    Activate
@endcomponent

Yours,<br>
{{ config('app.name') }}

@slot('subcopy')
    If you’re having trouble clicking the "Activate" button, copy and paste the URL below into your web browser:
    [{{ $acceptation_url }}]({{ $acceptation_url }})
@endslot
@endcomponent
