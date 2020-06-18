<?php


namespace Tests\Feature\Http\Controllers;

use App\Payment\Contracts\PaymentServiceContract;
use App\User;
use Tests\Mocks\PaymentHandlerMock;
use Tests\Mocks\PaymentServiceMock;
use Tests\TestCase;

class PaymentsControllerTest extends TestCase
{

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
