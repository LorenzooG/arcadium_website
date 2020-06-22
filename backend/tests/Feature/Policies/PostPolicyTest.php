<?php


namespace Tests\Feature\Policies;

use App\Post;
use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class PostPolicyTest extends TestCase
{
  public function testShouldCanUpdatePostWhenHavePermissionUpdateAnyPost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_ANY_POST
    ]));

    $post = factory(Post::class)->make();

    $this->assertTrue($user->can('update', $post));
  }

  public function testShouldCanUpdatePostWhenHavePermissionUpdatePostAndOwnThePost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_POST
    ]));

    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    $this->assertTrue($user->can('update', $post));
  }

  public function testShouldCanDeletePostWhenHavePermissionDeleteAnyPost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_ANY_POST
    ]));

    $this->assertTrue($user->can('delete', Post::class));
  }

  public function testShouldCanDeletePostWhenHavePermissionDeletePostAndOwnThePost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_POST
    ]));

    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    $this->assertTrue($user->can('delete', $post));
  }

  public function testShouldCanLikePostWhenHavePermissionLikePost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::LIKE_POST
    ]));

    $this->assertTrue($user->can('like', Post::class));
  }

  public function testShouldCanUnlikePostWhenHavePermissionUnlikePost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UNLIKE_POST
    ]));

    $this->assertTrue($user->can('unlike', Post::class));
  }

  public function testShouldCanCreatePostWhenHavePermissionStorePost()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_POST
    ]));

    $this->assertTrue($user->can('create', Post::class));
  }
}
