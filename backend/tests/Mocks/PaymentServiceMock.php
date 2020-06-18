<?php


namespace Tests\Mocks;


use App\Payment\Contracts\PaymentHandlerContract;
use App\Payment\Contracts\PaymentServiceContract;

final class PaymentServiceMock implements PaymentServiceContract
{

  private PaymentHandlerContract $handlerMock;

  public function __construct(PaymentHandlerContract $handlerMock)
  {
    $this->handlerMock = $handlerMock;
  }

  public function findPaymentHandlerByPaymentMethodString(string $paymentMethod): PaymentHandlerContract
  {
    return $this->handlerMock;
  }
}
