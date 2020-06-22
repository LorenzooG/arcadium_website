<?php


namespace Tests\Feature\Policies;

use App\Comment;
use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class CommentPolicyTest extends TestCase
{
  public function testShouldCanUpdateCommentWhenHavePermissionUpdateAnyComment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_ANY_COMMENT
    ]));

    $comment = factory(Comment::class)->make();

    $this->assertTrue($user->can('update', $comment));
  }

  public function testShouldCanUpdateCommentWhenHavePermissionUpdateCommentAndOwnTheComment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_COMMENT
    ]));

    $comment = factory(Comment::class)->make([
      'user_id' => $user->id
    ]);

    $this->assertTrue($user->can('update', $comment));
  }

  public function testShouldCanDeleteCommentWhenHavePermissionDeleteAnyComment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_ANY_COMMENT
    ]));

    $this->assertTrue($user->can('delete', Comment::class));
  }

  public function testShouldCanDeleteCommentWhenHavePermissionDeleteCommentAndOwnTheComment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_COMMENT
    ]));

    $comment = factory(Comment::class)->make([
      'user_id' => $user->id
    ]);

    $this->assertTrue($user->can('delete', $comment));
  }

  public function testShouldCanCreateCommentWhenHavePermissionStoreComment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_COMMENT
    ]));

    $this->assertTrue($user->can('create', Comment::class));
  }
}
