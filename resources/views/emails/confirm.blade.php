Hola {{ $user->name }}
Ha cambiado tu correo electroinico. Por favor verificaló usando el siguiente enlace:

{{ route('verify', $user->verification_token) }}