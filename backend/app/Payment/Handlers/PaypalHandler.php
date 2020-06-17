<?php


namespace App\Payment\Handlers;


use App\Payment\Contracts\PaymentHandlerContract;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class PaypalHandler
 *
 * Paypal's payment handler
 *
 * @package App\Payment\Handlers
 */
final class PaypalHandler implements PaymentHandlerContract
{
  const KEY = 'PAYPAL';

  public function setupCredentials(): void
  {
    // TODO: Implement setupCredentials() method.
  }

  public function findMerchantOrderById($id)
  {
    // TODO: Implement findMerchantOrderById() method.
  }

  public function findItemById($id)
  {
    // TODO: Implement findItemById() method.
  }

  public function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): \Symfony\Component\HttpFoundation\Response
  {
    // TODO: Implement handleCheckout() method.
  }

  public function handleNotification(Request $request): Response
  {
    // TODO: Implement handleNotification() method.
  }
}
