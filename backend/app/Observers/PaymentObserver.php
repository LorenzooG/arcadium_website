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
    $payment->user->notify(new ProductPurchasedNotification($payment->products));
  }

  /**
   * Handle the payment "updated" event.
   *
   * @param Payment $payment
   * @return void
   */
  public function updated(Payment $payment)
  {
    if ($payment->total_paid < $payment->total_price) return;
    if ($payment->is_delivered) return;

    $payment->user->notify(new ProductPaidNotification($payment->products));
  }
}
