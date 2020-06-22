<?php

namespace App\Observers;

use App\Notifications\ProductPaidNotification;
use App\Notifications\ProductPurchasedNotification;
use App\Payment;
use App\Repositories\PaymentRepository;

class PaymentObserver
{
  private PaymentRepository $paymentRepository;

  /**
   * PaymentObserver constructor
   *
   * @param PaymentRepository $paymentRepository
   */
  public function __construct(PaymentRepository $paymentRepository)
  {
    $this->paymentRepository = $paymentRepository;
  }

  /**
   * Handle the payment "created" event.
   *
   * @param Payment $payment
   * @return void
   */
  public function created(Payment $payment)
  {
    $this->paymentRepository->flushCache();

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
    $this->paymentRepository->flushCache();

    if ($payment->total_paid < $payment->total_price) return;
    if ($payment->is_delivered) return;

    $payment->user->notify(new ProductPaidNotification($payment->products));
  }
}
