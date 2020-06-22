@php
  /** @var App\User $user */
@endphp

@component('mail::message')
  {{ trans('notifications.dear', ['name' => $user->name]) }}

  {{ trans('notifications.password.reset.message') }}

  @component('mail::button', ['url' => route('user.reset.password')."?token={$token}&email={$user->email}"])
    {{ trans('notifications.password.reset.button') }}
  @endcomponent

  {{ trans('notifications.thanks') }}
@endcomponent

