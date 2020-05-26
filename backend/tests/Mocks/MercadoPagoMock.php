<?php

namespace Tests\Mocks;

use App\PurchasedProduct;
use App\User;
use App\Utils\MercadoPago;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MercadoPago\Item;
use MercadoPago\MerchantOrder;
use MercadoPago\Payer;
use MercadoPago\Payment;

class MercadoPagoMock extends MercadoPago
{

  private User $user;
  /**
   * @var Collection<PurchasedProduct> $products
   */
  private Collection $products;
  private \App\Payment $payment;
  private array $extra;
  private array $itemExtra;


  public function __construct(\App\Payment $payment, array $extra = [], array $itemExtra = [])
  {
    $this->products = $payment->products()->get();
    $this->user = $payment->user()->first();
    $this->extra = $extra;
    $this->itemExtra = $itemExtra;
    $this->payment = $payment;
  }

  public function findPaymentById(int $id): Payment
  {
    return $this->findOrderById($id)->payments[0];
  }

  public function findOrderById(int $id): MerchantOrder
  {
    $order = new MerchantOrder();

    $order->id = 52932;
    $order->shipments = [];
    $order->status = "closed";
    $order->externalReference = "default";
    $order->preferenceId = $id;

    $order->marketplace = "NONE";
    $order->notificationUrl = url("api/checkout/ipn/mp");
    $order->dateCreated = "2019-04-02T14:35:35.000-04:00";
    $order->sponsorId = null;
    $order->shippingCost = 0;
    $order->siteId = 2591237;
    $order->refundedAmount = 0;

    $mpProducts = [];
    $totalAmount = 0;

    foreach ($this->products as $product) {
      $item = new Item();

      $title = config("app.payment_title");

      $title = Str::replaceArray("{amount}", [$product->amount], $title);

      $item->id = "{$product->product->id}_{$this->payment->id}";
      $item->title = $title;
      $item->quantity = $product->amount;
      $item->unit_price = $product->product->price;
      $item->currency_id = config("app.payment_currency");

      foreach ($this->itemExtra as $key => $value) {
        $item->$key = $value;
      }

      $mpProducts[] = $item;
      $totalAmount += $product->product->price * $product->amount;
    }

    $order->items = $mpProducts;

    $order->cancelled = false;
    $order->additionalInfo = "";

    $payment = new Payment();

    $payment->id = 32953725;
    $payment->order = $order;
    $payment->transaction_amount = $totalAmount;
    $payment->total_paid_amount = $totalAmount;
    $payment->currency_id = "BRL";
    $payment->status = "approved";
    $payment->status_detail = "accredited";
    $payment->operation_type = "regular_payment";
    $payment->date_approved = "2019-04-02T14:35:35.000-04:00";
    $payment->date_created = "2019-04-02T14:35:35.000-04:00";
    $payment->last_modified = "2019-04-02T14:35:35.000-04:00";
    $payment->amount_refunded = 0;

    $order->totalAmount = $totalAmount;
    $order->paidAmount = $totalAmount;

    $payer = new Payer();

    $payer->email = $this->user->email;

    $order->payments = [
      $payment
    ];

    foreach ($this->extra as $key => $value) {
      $order->$key = $value;
    }

    return $order;
  }

}
