<?php


namespace App\Payment\Handlers;

use App\Payment\Contracts\PaymentHandlerContract;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PaypalPaymentHandler
 *
 * @package App\Payment\Handlers
 */
final class PaypalPaymentHandler implements PaymentHandlerContract
{
  const KEY = 'PAYPAL';

  public final function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response
  {
    // TODO: Implement handleCheckout() method.
  }

  public final function handleNotification(Request $request): Response
  {
    // TODO: Implement handleNotification() method.
  }

  public function getNotificationUrl(): string
  {
    // TODO: Implement getNotificationUrl() method.
  }
}
