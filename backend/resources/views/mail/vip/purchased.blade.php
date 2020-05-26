@component('mail::message')
  # Olá, {{ $user->name }}

  Parabéns pela aquisição!

  Aguardando pagamento...

  Obrigado,<br>
  {{ config('app.name') }}
@endcomponent
