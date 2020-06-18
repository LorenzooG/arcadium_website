<?php


namespace Tests\Feature\Payment\Handlers;

use App\Notifications\ProductPaidNotification;
use App\Notifications\ProductPurchasedNotification;
use App\Payment;
use App\Payment\Contracts\PaymentHandlerContract;
use App\Payment\Handlers\MercadoPagoPaymentHandler;
use App\Payment\Repositories\MercadoPagoPaymentRepository;
use App\Product;
use App\PurchasedProduct;
use App\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\Mocks\MercadoPagoPaymentRepositoryMock;
use Tests\TestCase;

class MercadoPagoHandlerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * @throws BindingResolutionException
   */
  public function testShouldCheckoutPayment()
  {
    Notification::fake();

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

    Notification::assertSentTo($user, ProductPurchasedNotification::class);

    $this->assertCount(1, $payments);
    $this->assertCount(1, $purchasedProducts);
    $this->assertCount(1, $paymentsPurchasedProducts);
  }

  /**
   * @throws BindingResolutionException
   */
  public function testShouldReceiveNotification()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var Product $product */
    $product = factory(Product::class)->create();
    $amount = 1;

    /** @var Payment $payment */
    $payment = $user->payments()->create([
      'user_name' => $user->name,
      'total_price' => $product->price * $amount,
      'payment_method' => MercadoPagoPaymentHandler::KEY,
      'origin_address' => $this->faker->ipv4
    ]);

    $payment->products()->save($product, [
      'amount' => $amount
    ]);

    $notificationUrlMock = $this->faker->url;
    $preferencePaymentIdMock = $this->faker->numberBetween(1, 1000);

    $this->app->singleton(MercadoPagoPaymentRepository::class, function () use ($payment, $notificationUrlMock, $preferencePaymentIdMock) {
      return $this->app->make(MercadoPagoPaymentRepositoryMock::class, [
        'paymentMock' => $payment,
        'notificationUrl' => $notificationUrlMock,
        'preferencePaymentMockId' => $preferencePaymentIdMock
      ]);
    });

    /** @var PaymentHandlerContract $handler */
    $handler = $this->app->make(MercadoPagoPaymentHandler::class);

    $request = Request::create(route('payments.notifications', [
      'paymentHandler' => $this->faker->word
    ]), 'POST');

    $request->query->set('id', $preferencePaymentIdMock);
    $request->query->set('topic', 'merchant_order');

    $response = $handler->handleNotification($request);

    Notification::assertSentTo($user, ProductPaidNotification::class);

    $this->assertEquals(204, $response->getStatusCode());
  }

}
