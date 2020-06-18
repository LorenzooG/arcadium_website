<?php

namespace App\Observers;

use App\Notifications\ProductPaidNotification;
use App\Notifications\ProductPurchasedNotification;
use App\Payment;

class PaymentObserver
{
  /**
   * Handle the payment "created" event.
   *
   * @param Payment $payment
   * @return void
   */
  public function created(Payment $payment)
  {
    $payment->user->notify(new ProductPurchasedNotification($payment->products()->get()->map(function ($item) {
      return $item['product'];
    })));
  }

  /**
   * Handle the payment "updated" event.
   *
   * @param Payment $payment
   * @return void
   */
  public function updated(Payment $payment)
  {
    if (!$payment->is_delivered) return;

    $payment->user->notify(new ProductPaidNotification($payment->products()->get()->map(function ($item) {
      return $item['product'];
    })));
  }
}
