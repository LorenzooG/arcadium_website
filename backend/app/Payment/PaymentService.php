<?php


namespace App\Payment;


use App\Payment\Contracts\PaymentHandlerContract;
use App\Payment\Contracts\PaymentServiceContract;
use App\Payment\Handlers\BankSlipPaymentHandler;
use App\Payment\Handlers\MercadoPagoPaymentHandler;
use App\Payment\Handlers\PaypalPaymentHandler;
use Illuminate\Foundation\Application;

/**
 * Class PaymentService
 *
 * @package App\Payment
 */
final class PaymentService implements PaymentServiceContract
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
   * @param Application $app
   */
  public final function __construct(Application $app)
  {
    $this->paymentHandlers = [];

    $this->paymentHandlers[MercadoPagoPaymentHandler::KEY] = $app->make(MercadoPagoPaymentHandler::class);
    $this->paymentHandlers[PaypalPaymentHandler::KEY] = $app->make(PaypalPaymentHandler::class);
    $this->paymentHandlers[BankSlipPaymentHandler::KEY] = $app->make(BankSlipPaymentHandler::class);
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
