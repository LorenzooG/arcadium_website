<?php

namespace App\Policies;

use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class RolePolicy
 *
 * @package App\Policies
 */
final class RolePolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can create models.
   *
   * @param User $user
   * @return mixed
   */
  public final function create(User $user)
  {
    return $user->hasPermission(Permission::STORE_ROLE);
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @return mixed
   */
  public final function update(User $user)
  {
    return $user->hasPermission(Permission::UPDATE_ROLE);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @return mixed
   */
  public final function delete(User $user)
  {
    return $user->hasPermission(Permission::DELETE_ROLE);
  }

  /**
   * Determine whether the user can attach role to any user
   *
   * @param User $user
   * @return bool
   */
  public final function attach(User $user)
  {
    return $user->hasPermission(Permission::ATTACH_ROLE_TO_USER);
  }

  /**
   * Determine whether the user can detach role to any user
   *
   * @param User $user
   * @return bool
   */
  public final function detach(User $user)
  {
    return $user->hasPermission(Permission::DETACH_ROLE_TO_USER);
  }
}
