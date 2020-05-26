<?php

namespace App\Utils;

use MercadoPago\MerchantOrder;
use MercadoPago\Payment;

class MercadoPago
{

  public static ?MercadoPago $instance = null;

  public function accessToken(): string
  {
    return config("app.mp_access_token");
  }

  public function findOrderById(int $id): MerchantOrder
  {
    return MerchantOrder::find_by_id($id);
  }

  public function findPaymentById(int $id): Payment
  {
    return MerchantOrder::find_by_id($id);
  }

  public static function instance(): self
  {
    if (self::$instance === null) {
      self::$instance = new MercadoPago();
    }
    return self::$instance;
  }

}
