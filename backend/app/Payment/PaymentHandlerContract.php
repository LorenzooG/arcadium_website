<?php


namespace App\Payment\Contracts;

use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface PaymentHandlerContract
{

  public function setupCredentials(): void;

  public function findMerchantOrderById($id);

  public function findItemById($id);

  public function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response;

  public function handleNotification(Request $request): Response;

}
