<?php


namespace App\Payment\Contracts;


interface PaymentRepositoryContract
{

  public function findMerchantOrderById($id);

  public function findItemById($id);

}
