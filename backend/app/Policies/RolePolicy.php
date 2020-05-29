<?php

namespace App\Policies;

use App\Role;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
    return $user->hasPermission(Permission::VIEW_ANY_ROLE);
  }

  /**
   * Determine whether the user can view the model.
   *
   * @param User $user
   * @param Role $role
   * @return mixed
   */
  public function view(User $user, Role $role)
  {
    return $user->hasPermission(Permission::VIEW_ROLE)
      && $user->roles()->where('role_id', $role->id)->exists();
  }

  /**
   * Determine whether the user can create models.
   *
   * @param User $user
   * @return mixed
   */
  public function create(User $user)
  {
    return $user->hasPermission(Permission::STORE_ROLE);
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @return mixed
   */
  public function update(User $user)
  {
    return $user->hasPermission(Permission::UPDATE_ROLE);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @return mixed
   */
  public function delete(User $user)
  {
    return $user->hasPermission(Permission::DELETE_ROLE);
  }

  /**
   * Determine whether the user can view self roles
   *
   * @param User $user
   * @return bool
   */
  public function viewSelf(User $user)
  {
    return $user->hasPermission(Permission::VIEW_SELF_ROLES);
  }

  /**
   * Determine whether the user can attach role to any user
   *
   * @param User $user
   * @return bool
   */
  public function attach(User $user)
  {
    return $user->hasPermission(Permission::ATTACH_ROLE_TO_USER);
  }

  /**
   * Determine whether the user can detach role to any user
   *
   * @param User $user
   * @return bool
   */
  public function detach(User $user)
  {
    return $user->hasPermission(Permission::DETACH_ROLE_TO_USER);
  }
}
