<?php


namespace App\Payment\Contracts;


interface PaymentServiceContract
{

  /**
   * Find payment handler by its string key
   *
   * @param string $paymentMethod
   * @return PaymentHandlerContract
   */
  public function findPaymentHandlerByPaymentMethodString(string $paymentMethod): PaymentHandlerContract;

}
