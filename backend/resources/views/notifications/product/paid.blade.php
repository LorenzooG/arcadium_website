@php
  /** @var App\User $user */
  /** @var Illuminate\Support\Collection $products */
@endphp

<style>
  .border-bottom {
    border-bottom: 1px #cbcbcb solid;
    padding: 1em 0;
  }
</style>

@component('mail::message')
  {{ trans('notifications.dear', ['name' => $user->name]) }}

  {{ trans('notifications.product.paid.message') }}

  @component('mail::panel')
    @foreach($products as $product)
      <div class='border-bottom'>
        {{ trans('notifications.item', ['item' => $product->title]) }} <br/><br/>
        {{ trans('notifications.amount', ['amount' => $product->pivot->amount]) }}
      </div>
    @endforeach
  @endcomponent

  {{ trans('notifications.thanks') }}
@endcomponent

