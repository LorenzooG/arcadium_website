<?php


namespace App\Payment\Repositories;


use App\Payment\Contracts\PaymentRepositoryContract;

/**
 * Class PaypalPaymentRepository
 *
 * Paypal's payment repository
 *
 * @package App\Payment\Repositories
 */
final class PaypalPaymentRepository implements PaymentRepositoryContract
{
  public function findMerchantOrderById($id)
  {
    // TODO: Implement findMerchantOrderById() method.
  }

  public function findItemById($id)
  {
    // TODO: Implement findItemById() method.
  }
}
