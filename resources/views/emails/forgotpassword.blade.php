@component('mail::message')
    Hi

    There was a request to change your password!

    Use your secret code!

    {{ $token }}

    If you did not make this request then please ignore this email.

    Thanks,
    {{ config('app.name') }}
@endcomponent
