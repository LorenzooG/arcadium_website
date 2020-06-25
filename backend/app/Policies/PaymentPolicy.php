<?php

namespace App\Policies;

use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can view the model.
   *
   * @param User $user
   * @return mixed
   */
  public function view(User $user)
  {
    return $user->hasPermission(Permission::VIEW_PAYMENT);
  }

  /**
   * Determine whether the user can checkout a payment.
   *
   * @param User $user
   * @return mixed
   */
  public function checkout(User $user)
  {
    return $user->hasPermission(Permission::CHECKOUT_PAYMENT);
  }
}
