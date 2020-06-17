<?php


namespace App\Payment;


use App\Payment\Contracts\PaymentHandlerContract;
use App\Payment\Handlers\BankSlipHandler;
use App\Payment\Handlers\MercadoPagoHandler;
use App\Payment\Handlers\PaypalHandler;

/**
 * Class PaymentService
 *
 * @package App\Payment
 */
final class PaymentService
{

  /**
   * Application payment handlers
   *
   * @var array<string,PaymentHandlerContract>
   */
  protected array $paymentHandlers;

  /**
   * PaymentService constructor
   *
   * Setup payment handlers
   *
   * @param MercadoPagoHandler $mercadoPagoHandler
   * @param PaypalHandler $paypalHandler
   * @param BankSlipHandler $bankSlipHandler
   */
  public final function __construct(MercadoPagoHandler $mercadoPagoHandler, PaypalHandler $paypalHandler, BankSlipHandler $bankSlipHandler)
  {
    $this->paymentHandlers = [];

    $this->paymentHandlers[MercadoPagoHandler::KEY] = $mercadoPagoHandler;
    $this->paymentHandlers[PaypalHandler::KEY] = $paypalHandler;
    $this->paymentHandlers[BankSlipHandler::KEY] = $bankSlipHandler;
  }

  /**
   * Find payment handler by its string key
   *
   * @param string $paymentMethod
   * @return PaymentHandlerContract
   */
  public final function findPaymentHandlerByPaymentMethodString(string $paymentMethod): PaymentHandlerContract
  {
    return $this->paymentHandlers[$paymentMethod];
  }

}
