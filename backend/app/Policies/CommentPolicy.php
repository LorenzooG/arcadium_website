<?php

namespace App\Policies;

use App\Comment;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class CommentPolicy
 *
 * @package App\Policies
 */
final class CommentPolicy
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
    return $user->hasPermission(Permission::STORE_COMMENT);
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param User $user
   * @param Comment $comment
   * @return mixed
   */
  public final function update(User $user, ?Comment $comment = null)
  {
    return $user->hasPermission(Permission::UPDATE_ANY_COMMENT)
      || ($user->hasPermission(Permission::UPDATE_COMMENT)
        && optional($comment)->user_id === $user->id);
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param User $user
   * @param Comment $comment
   * @return mixed
   */
  public final function delete(User $user, ?Comment $comment = null)
  {
    return $user->hasPermission(Permission::DELETE_ANY_COMMENT)
      || ($user->hasPermission(Permission::DELETE_COMMENT)
        && optional($comment)->user_id === $user->id);
  }

}
