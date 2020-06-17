<?php


namespace Tests\Feature\Payment\Handlers;

use App\Payment\Contracts\PaymentRepositoryContract;
use App\Payment\Repositories\MercadoPagoPaymentRepository;
use App\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\Mocks\MercadoPagoPaymentRepositoryMock;
use Tests\TestCase;

class MercadoPagoHandlerTest extends TestCase
{

  private function getDefaultPaymentAttributes()
  {
    return [
      'user_name' => $this->faker->name,
      'total_price' => 0,
      'origin_address' => '127.0.0.1'
    ];
  }

  /**
   * @throws BindingResolutionException
   */
  public function test()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $payment = $user->payments()->create($this->getDefaultPaymentAttributes());
    $preferencePaymentIdMock = 1000;
    $preferenceOrderIdMock = 1000;

    $this->app->singleton(MercadoPagoPaymentRepository::class, function () use ($payment, $preferencePaymentIdMock) {
      return $this->app->make(MercadoPagoPaymentRepositoryMock::class, [
        'paymentMock' => $payment,
        'preferencePaymentMockId' => $preferencePaymentIdMock,
        'notificationUrl' => 'fuck.com',
        'productsMock' => []
      ]);
    });

    /** @var PaymentRepositoryContract $handler */
    $handler = $this->app->make(MercadoPagoPaymentRepository::class);

    get_class($handler);

    $this->assertNotNull($handler->findMerchantOrderById($preferenceOrderIdMock));
  }

}
