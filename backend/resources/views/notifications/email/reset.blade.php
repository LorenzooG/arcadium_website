@php
  /** @var App\User $user */
@endphp

@component('mail::message')
  {{ trans('notifications.dear', ['name' => $user->name]) }}

  {{ trans('notifications.email.reset.message') }}

  @component('mail::button', ['url' => route('user.update.email', ['emailUpdate'=>$token])])
    {{ trans('notifications.email.reset.button') }}
  @endcomponent

  {{ trans('notifications.thanks') }}
@endcomponent

