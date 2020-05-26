@component('mail::message')
  # Olá, {{ $user->name }}

  Pagamento confirmado, parabéns pela arquisição!

  Para receber o vip, basta entrar no servidor com o user name da sua conta

  Obrigado,<br>
  {{ config('app.name') }}
@endcomponent
