<?php


namespace Tests\Feature\Policies;

use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class UserPolicyTest extends TestCase
{
  public function testShouldCanUpdateUserWhenHavePermissionUpdateAnyUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_ANY_USER
    ]));

    $this->assertTrue($user->can('update', User::class));
  }

  public function testShouldCanCreateUserWhenHavePermissionUpdateStoreUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_USER
    ]));

    $this->assertTrue($user->can('create', User::class));
  }

  public function testShouldCanDeleteUserWhenHavePermissionDeleteAnyUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_ANY_USER
    ]));

    $this->assertTrue($user->can('delete', User::class));
  }

  public function testShouldCanRestoreUserWhenHavePermissionRestoreAnyUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::RESTORE_ANY_USER
    ]));

    $this->assertTrue($user->can('restore', User::class));
  }

  public function testShouldCanViewTrashedUsersWhenHavePermissionViewTrashedUsers()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_TRASHED_USERS
    ]));

    $this->assertTrue($user->can('viewTrashed', User::class));
  }

  public function testShouldCanUpdateSelfWhenHavePermissionUpdateUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_USER
    ]));

    $this->assertTrue($user->can('update_self'));
  }

  public function testShouldCanDeleteSelfWhenHavePermissionDeleteUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_USER
    ]));

    $this->assertTrue($user->can('delete_self'));
  }
}
