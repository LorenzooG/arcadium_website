<?php


namespace App\Payment\Handlers;


use App\Payment\Contracts\PaymentHandlerContract;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class BankSlipHandler
 *
 * "Boleto bancário"'s payment handler
 *
 * @package App\Payment\Handlers
 */
final class BankSlipHandler implements PaymentHandlerContract
{
  const KEY = 'BANK_SLIP';

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

  public function handleCheckout(User $user, array $items): Response
  {
    // TODO: Implement handleCheckout() method.
  }

  public function handleNotification(Request $request): Response
  {
    // TODO: Implement handleNotification() method.
  }
}
