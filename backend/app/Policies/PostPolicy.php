<?php

namespace App\Policies;

use App\Post;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
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
    return true;
  }

  /**
   * Determine whether the user can view the model.
   *
   * @param User $user
   * @param Post $post
   * @return mixed
   */
  public function view(User $user, Post $post)
  {
    return true;
  }

  /**
   * Determine whether the user can create models.
   *
   * @param User $user
   * @return mixed
   */
  public function create(User $user)
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
  public function update(User $user, ?Post $post = null)
  {
    return $user->hasPermission(Permission::DELETE_ANY_POST)
      || ($user->hasPermission(Permission::UPDATE_POST)
        && $user->posts->contains($post));
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @param Post|null $post
   * @return mixed
   */
  public function delete(User $user, ?Post $post = null)
  {
    return $user->hasPermission(Permission::DELETE_ANY_POST)
      || ($user->hasPermission(Permission::DELETE_POST)
        && $user->posts->contains($post));
  }

  public function like(User $user)
  {
    return $user->hasPermission(Permission::LIKE_POST);
  }
}
