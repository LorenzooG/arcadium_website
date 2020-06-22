@php
  /** @var App\User $user */
@endphp

@component('mail::message')
  {{ trans('notifications.dear', ['name' => $user->name]) }}

  {{ trans('notifications.password.reseted.message', ['now' => now()->toDateTimeLocalString()]) }}

  {{ trans('notifications.thanks') }}
@endcomponent

