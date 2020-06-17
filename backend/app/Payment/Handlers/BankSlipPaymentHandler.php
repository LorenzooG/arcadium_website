<?php


namespace App\Payment\Handlers;


use App\Payment\Contracts\PaymentHandlerContract;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BankSlipPaymentHandler
 *
 * @package App\Payment\Handlers
 */
final class BankSlipPaymentHandler implements PaymentHandlerContract
{
  const KEY = 'BANK_SLIP';

  public function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response
  {
    // TODO: Implement handleCheckout() method.
  }

  public function handleNotification(Request $request): Response
  {
    // TODO: Implement handleNotification() method.
  }

  public function getNotificationUrl(): string
  {
    // TODO: Implement getNotificationUrl() method.
  }
}
