<?php


namespace App\Payment\Contracts;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface PaymentHandlerContract
{

  public function setupCredentials(): void;

  public function findMerchantOrderById($id);

  public function findItemById($id);

  public function handleCheckout(User $user, array $items): Response;

  public function handleNotification(Request $request): Response;

}
