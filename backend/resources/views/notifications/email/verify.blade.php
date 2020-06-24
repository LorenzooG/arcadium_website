@php
  /** @var App\User $user */
@endphp

@component('mail::message')
  {{ trans('notifications.dear', ['name' => $user->name]) }}

  {{ trans('notifications.email.verify.message') }}

  @component('mail::button', ['url' => $link])
    {{ trans('notifications.email.verify.button') }}
  @endcomponent

  {{ trans('notifications.thanks') }}
@endcomponent

