<?php


namespace Tests\Feature\Policies;

use App\Payment;
use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class PaymentPolicyTest extends TestCase
{
  public function testShouldCanViewPaymentWhenHavePermissionViewPayment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_PAYMENT
    ]));

    $this->assertTrue($user->can('view', Payment::class));
  }

  public function testShouldCanCheckoutPaymentWhenHavePermissionCheckoutPayment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::CHECKOUT_PAYMENT
    ]));

    $this->assertTrue($user->can('checkout', Payment::class));
  }
}
