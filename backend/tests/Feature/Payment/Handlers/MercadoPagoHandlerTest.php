<?php


namespace Tests\Feature\Payment\Handlers;

use App\Payment;
use App\Payment\Contracts\PaymentHandlerContract;
use App\Payment\Handlers\MercadoPagoPaymentHandler;
use App\Product;
use App\PurchasedProduct;
use App\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class MercadoPagoHandlerTest extends TestCase
{

  /**
   * @throws BindingResolutionException
   */
  public function testShouldCheckoutPayment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    /** @var Product $product */
    $product = factory(Product::class)->create();

    /** @var PaymentHandlerContract $handler */
    $handler = $this->app->make(MercadoPagoPaymentHandler::class);

    $ipAddress = $this->faker->ipv4;
    $userName = $user->user_name;

    $amount = 1;

    $response = $handler->handleCheckout($user, $userName, $ipAddress, [
      [
        'product' => $product->id,
        'amount' => $amount
      ]
    ]);

    $responseContent = json_decode($response->getContent(), true);

    $payments = Payment::query()
      ->where('id', $responseContent['id'])
      ->where('origin_address', $ipAddress)
      ->where('user_name', $userName)
      ->where('user_id', $user->id)
      ->where('total_price', $amount * $product->price)
      ->get();

    $paymentsPurchasedProducts = PurchasedProduct::query()
      ->where('payment_id', $responseContent['id'])
      ->get();

    $purchasedProducts = PurchasedProduct::query()
      ->where('product_id', $product->id)
      ->where('payment_id', $responseContent['id'])
      ->where('amount', $amount)
      ->get();

    $this->assertCount(1, $payments);
    $this->assertCount(1, $purchasedProducts);
    $this->assertCount(1, $paymentsPurchasedProducts);
  }

}
