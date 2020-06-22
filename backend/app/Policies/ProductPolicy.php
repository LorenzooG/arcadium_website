<?php

namespace App\Policies;

use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ProductPolicy
 *
 * @package App\Policies
 */
final class ProductPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can create models.
   *
   * @param User $user
   * @return mixed
   */
  public function create(User $user)
  {
    return $user->hasPermission(Permission::STORE_PRODUCT);
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @return mixed
   */
  public function update(User $user)
  {
    return $user->hasPermission(Permission::UPDATE_PRODUCT);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @return mixed
   */
  public function delete(User $user)
  {
    return $user->hasPermission(Permission::DELETE_PRODUCT);
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param User $user
   * @return mixed
   */
  public final function restore(User $user)
  {
    return $user->hasPermission(Permission::RESTORE_ANY_PRODUCT);
  }

  /**
   * Determine whether the user can view trashed models.
   *
   * @param User $user
   * @return mixed
   */
  public final function viewTrashed(User $user)
  {
    return $user->hasPermission(Permission::VIEW_TRASHED_PRODUCTS);
  }
}
