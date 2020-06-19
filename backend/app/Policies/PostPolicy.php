<?php

namespace App\Policies;

use App\Post;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class PostPolicy
 *
 * @package App\Policies
 */
final class PostPolicy
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
    return $user->hasPermission(Permission::STORE_POST);
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @param Post|null $post
   * @return mixed
   */
  public final function update(User $user, ?Post $post = null)
  {
    return $user->hasPermission(Permission::DELETE_ANY_POST)
      || ($user->hasPermission(Permission::UPDATE_POST)
        && $user->is($post->user));
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @param Post|null $post
   * @return mixed
   */
  public final function delete(User $user, ?Post $post = null)
  {
    return $user->hasPermission(Permission::DELETE_ANY_POST)
      || ($user->hasPermission(Permission::DELETE_POST)
        && $user->is($post->user));
  }

  public final function like(User $user)
  {
    return $user->hasPermission(Permission::LIKE_POST);
  }
}
