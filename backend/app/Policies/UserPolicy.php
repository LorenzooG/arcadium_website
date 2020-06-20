<?php

namespace App\Policies;

use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 *
 * @package App\Policies
 */
final class UserPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @return mixed
   */
  public final function update(User $user)
  {
    return $user->hasPermission(Permission::UPDATE_ANY_USER);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @return mixed
   */
  public final function delete(User $user)
  {
    return $user->hasPermission(Permission::DELETE_ANY_USER);
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param User $user
   * @return mixed
   */
  public final function restore(User $user)
  {
    return $user->hasPermission(Permission::RESTORE_ANY_USER);
  }

  /**
   * Determine whether the user can view trashed models.
   *
   * @param User $user
   * @return mixed
   */
  public final function viewTrashed(User $user)
  {
    return $user->hasPermission(Permission::VIEW_TRASHED_USERS);
  }
}
