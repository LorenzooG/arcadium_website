<?php


namespace Tests\Feature\Http\Controllers;

use App\Payment;
use App\Payment\Contracts\PaymentServiceContract;
use App\Product;
use App\PurchasedProduct;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\Mocks\PaymentHandlerMock;
use Tests\Mocks\PaymentServiceMock;
use Tests\TestCase;

class PaymentsControllerTest extends TestCase
{

  public function testShouldShowAllPaymentsWhenGetPayments()
  {
    Notification::fake();
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    factory(Payment::class, 3)->create([
      'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->getJson(route('payments.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(Payment::query()->orderByDesc('id')->paginate()->items())->map(function (Payment $item) {
          return [
            'id' => $item->id,
            'user_name' => $item->user_name,
            'total_paid' => $item->total_paid,
            'total_price' => $item->total_price,
            'is_delivered' => $item->is_delivered,
            'origin_ip_address' => $item->origin_address,
            'payment_method' => $item->payment_method,
            'products' => route('payments.products', [
              'payment' => $item->id
            ]),
            'user' => route('users.show', [
              'user' => $item->user_id
            ]),
            'created_at' => $item->created_at->toISOString(),
            'updated_at' => $item->updated_at->toISOString(),
          ];
        })->toArray()
      ]);
  }

  public function testShouldShowAPaymentWhenGetPayments()
  {
    Notification::fake();
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Payment $payment */
    $payment = factory(Payment::class)->create([
      'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->getJson(route('payments.show', [
      'payment' => $payment->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $payment->id,
        'user_name' => $payment->user_name,
        'total_paid' => $payment->total_paid,
        'total_price' => $payment->total_price,
        'is_delivered' => $payment->is_delivered,
        'origin_ip_address' => $payment->origin_address,
        'payment_method' => $payment->payment_method,
        'products' => route('payments.products', [
          'payment' => $payment->id
        ]),
        'user' => route('users.show', [
          'user' => $payment->user_id
        ]),
        'created_at' => $payment->created_at->toISOString(),
        'updated_at' => $payment->updated_at->toISOString(),
      ]);
  }

  public function testShouldShowAllPaymentProductsWhenGetPaymentsProducts()
  {
    Notification::fake();
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Product $product */
    $product = factory(Product::class)->create();
    $amount = 1;
    /** @var Payment $payment */
    $payment = factory(Payment::class)->create([
      'user_id' => $user->id
    ]);
    $payment->products()->save($product, [
      'amount' => $amount
    ]);

    $response = $this->actingAs($user)->getJson(route('payments.products', [
      'payment' => $payment->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(PurchasedProduct::query()->paginate()->items())->map(function (PurchasedProduct $item) use ($product) {
          return [
            'product' => route('products.show', [
              'product' => $product->id
            ]),
            'amount' => $item->amount,
          ];
        })->toArray()
      ]);
  }

  public function testShouldSendNotificationToWebsiteWhenPostPaymentsNotifications()
  {
    $this->app->singleton(PaymentServiceContract::class, function () {
      return new PaymentServiceMock($this->app->make(PaymentHandlerMock::class));
    });

    $response = $this->postJson(route('payments.notifications', [
      'paymentHandler' => $this->faker->text(72)
    ]));

    $response->assertNoContent();
  }

  public function testShouldCheckoutPaymentWhenPostPaymentsCheckout()
  {
    /** @var User $user */
    $user = factory(User::class)->create();

    $idMock = $this->faker->numberBetween(1, 1000);
    $preferenceIdMock = $this->faker->numberBetween(1, 1000);
    $linkMock = $this->faker->text(72);

    $this->app->singleton(PaymentServiceContract::class, function () use ($idMock, $preferenceIdMock, $linkMock) {
      return new PaymentServiceMock($this->app->make(PaymentHandlerMock::class, [
        'idMock' => $idMock,
        'preferenceIdMock' => $preferenceIdMock,
        'linkMock' => $linkMock
      ]));
    });

    $response = $this->actingAs($user)->postJson(route('payments.checkout', [
      'paymentHandler' => $this->faker->text(10)
    ]), [
      'user_name' => $user->user_name,
      'items' => []
    ]);

    $response->assertOk()
      ->assertJson([
        'id' => $idMock,
        'preference_id' => $preferenceIdMock,
        'link' => $linkMock
      ]);
  }

}
