@component('mail::message')
# Hola {{ $user->name }}

Ha cambiado tu correo electroinico. Por favor verificaló usando el siguiente enlace:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verificar
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent