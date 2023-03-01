@component('mail::message')
<h1>Hi {{$user->name}} {{$user->last_name}}</h1>

We are happy that you signed up for Departure Cloud.You are almost ready to get started. Pls click on the button below to verify your email ID  and start exploring thousands of live departures.

@component('mail::button', ['url' => $verifyUrl])
Verify Email Address
@endcomponent


Regards,<br>
{{ config('app.name') }}

@endcomponent