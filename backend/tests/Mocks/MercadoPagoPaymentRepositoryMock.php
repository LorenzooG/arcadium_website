<?php


namespace Tests\Mocks;

use App\Payment;
use App\Payment\Contracts\PaymentRepositoryContract;
use App\Product;
use Exception;
use MercadoPago\Item as MercadoPagoItem;
use MercadoPago\MerchantOrder as MercadoPagoMerchantOrder;
use MercadoPago\Payment as MercadoPagoPayment;

final class MercadoPagoPaymentRepositoryMock implements PaymentRepositoryContract
{
  private Payment $paymentMock;
  private array $productsMock;
  private int $preferencePaymentMockId;
  private string $notificationUrl;

  public function __construct(Payment $paymentMock, int $preferencePaymentMockId, string $notificationUrl, array $productsMock = [])
  {
    $this->preferencePaymentMockId = $preferencePaymentMockId;
    $this->paymentMock = $paymentMock;
    $this->productsMock = $productsMock;
    $this->notificationUrl = $notificationUrl;
  }

  /**
   * @param $id
   * @return MercadoPagoMerchantOrder
   * @throws Exception
   */
  public function findMerchantOrderById($id)
  {
    $itemsWithActualProducts = $this->paymentMock->products()->withPivot('amount')->get()->map(function ($item) {
      return [
        'product' => $item,
        'amount' => $item->pivot->amount
      ];
    });

    $totalPrice = $itemsWithActualProducts->reduce(function ($totalPrice, $item) {
      return $totalPrice + $item['product']->price * $item['amount'];
    }, 0);

    return new MercadoPagoMerchantOrder([
      'id' => $id,
      'shipments' => [],
      'status' => 'closed',
      'externalReference' => 'default',
      'preferenceId' => $id,
      'marketplace' => 'none',
      'notification_url' => $this->notificationUrl,
      'date_created' => '2019-04-02T14:35:35.000-04:00',
      'sponsor_id' => null,
      'shipping_cost' => 0,
      'site_id' => rand(0, 15),
      'refunded_amount' => 0,
      'cancelled' => false,
      'additional_info' => '',
      'totalAmount' => $totalPrice,
      'paidAmount' => $totalPrice,
      'items' => $itemsWithActualProducts->map(function ($item) {
        /** @var Product $product */
        $product = $item['product'];
        $amount = $item['amount'];

        return new MercadoPagoItem([
          'id' => "{$this->paymentMock->id}_{$product->id}",
          'title' => $product->title,
          'quantity' => $amount,
          'unit_price' => $product->price,
          'currency_id' => config('app.payment_currency')
        ]);
      }),
      'payments' => [
        new MercadoPagoPayment([
          'id' => $this->preferencePaymentMockId,
          'transaction_amount' => $totalPrice,
          'total_paid_amount' => $totalPrice,
          'currency_id' => config('app.payment_currency'),
          'status' => 'approved',
          'status_detail' => 'accredited',
          'operation_type' => 'regular_payment',
          'date_approved' => '2019-04-02T14:35:35.000-04:00',
          'date_created' => '2019-04-02T14:35:35.000-04:00',
          'last_modified' => '2019-04-02T14:35:35.000-04:00',
          'amount_refunded' => 0,
        ])
      ]
    ]);
  }

  /**
   * @param $id
   * @return mixed
   * @throws Exception
   */
  public function findItemById($id)
  {
    return $this->findMerchantOrderById($id)->getAttributes()['payments'][0];
  }
}
