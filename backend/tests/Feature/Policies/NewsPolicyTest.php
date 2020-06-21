<?php


namespace Tests\Feature\Policies;

use App\News;
use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class NewsPolicyTest extends TestCase
{
  public function testShouldCanUpdateNewsWhenHavePermissionUpdateNews()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_NEWS
    ]));

    $this->assertTrue($user->can('update', News::class));
  }

  public function testShouldCanCreateNewsWhenHavePermissionStoreNews()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_NEWS
    ]));

    $this->assertTrue($user->can('create', News::class));
  }

  public function testShouldCanDeleteNewsWhenHavePermissionDeleteNews()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_NEWS
    ]));

    $this->assertTrue($user->can('delete', News::class));
  }
}
