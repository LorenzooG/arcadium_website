<?php


namespace App\Payment\Repositories;

use App\Payment\Contracts\PaymentRepositoryContract;
use MercadoPago\MerchantOrder;
use MercadoPago\Payment as MercadoPagoPayment;

/**
 * Class MercadoPagoPaymentRepository
 *
 * MercadoPago's payment repository
 *
 * @package App\Payment\Repositories
 */
final class MercadoPagoPaymentRepository implements PaymentRepositoryContract
{
  public final function findMerchantOrderById($id): MerchantOrder
  {
    return MerchantOrder::find_by_id($id);
  }

  public final function findItemById($id): MercadoPagoPayment
  {
    return MercadoPagoPayment::find_by_id($id);
  }
}
