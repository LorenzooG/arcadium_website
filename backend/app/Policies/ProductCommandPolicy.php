<?php

namespace App\Policies;

use App\ProductCommand;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCommandPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can view any models.
   *
   * @param User $user
   * @return mixed
   */
  public function viewAny(User $user)
  {
    return $user->hasPermission(Permission::VIEW_PRODUCT_COMMANDS);
  }

  /**
   * Determine whether the user can view the model.
   *
   * @param User $user
   * @param ProductCommand $productCommand
   * @return mixed
   */
  public function view(User $user, ProductCommand $productCommand)
  {
    return $user->hasPermission(Permission::VIEW_PRODUCT_COMMANDS);
  }

  /**
   * Determine whether the user can create models.
   *
   * @param User $user
   * @return mixed
   */
  public function create(User $user)
  {
    return $user->hasPermission(Permission::STORE_PRODUCT_COMMAND);
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @return mixed
   */
  public function update(User $user)
  {
    return $user->hasPermission(Permission::UPDATE_PRODUCT_COMMAND);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @return mixed
   */
  public function delete(User $user)
  {
    return $user->hasPermission(Permission::DELETE_PRODUCT_COMMAND);
  }

}
