<?php

namespace App\Policies;

use App\Comment;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::STORE_COMMENT);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
      return $user->hasPermission(Permission::UPDATE_ANY_COMMENT)
        || ($user->hasPermission(Permission::UPDATE_COMMENT) 
                && $comment->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
      return $user->hasPermission(Permission::DELETE_ANY_COMMENT)
        || ($user->hasPermission(Permission::DELETE_COMMENT) 
                && $comment->user_id === $user->id);
    }

}
