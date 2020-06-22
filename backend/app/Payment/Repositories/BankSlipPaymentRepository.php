<?php


namespace App\Payment\Repositories;


use App\Payment\Contracts\PaymentRepositoryContract;

/**
 * Class BankSlipPaymentRepository
 *
 * "Boleto bancário"'s payment repository
 *
 * @package App\Payment\Repositories
 */
final class BankSlipPaymentRepository implements PaymentRepositoryContract
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
