<?php


namespace Tests\Feature\Policies;

use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class RolePolicyTest extends TestCase
{
  public function testShouldCanUpdateRoleWhenHavePermissionUpdateRole()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_ROLE
    ]));

    $this->assertTrue($user->can('update', Role::class));
  }

  public function testShouldCanViewRoleWhenHavePermissionViewRole()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_ROLE
    ]));

    $this->assertTrue($user->can('view', Role::class));
  }

  public function testShouldCanViewAnyRoleWhenHavePermissionViewAnyRole()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_ANY_ROLE
    ]));

    $this->assertTrue($user->can('viewAny', Role::class));
  }

  public function testShouldCanViewSelfRolesWhenHavePermissionViewAnyRole()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_ANY_ROLE
    ]));

    $this->assertTrue($user->can('viewSelf', Role::class));
  }

  public function testShouldCanViewSelfRolesWhenHavePermissionViewSelfRoles()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_SELF_ROLES
    ]));

    $this->assertTrue($user->can('viewSelf', Role::class));
  }

  public function testShouldCanAttachRoleWhenHavePermissionAttachRoleToUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::ATTACH_ROLE_TO_USER
    ]));

    $this->assertTrue($user->can('attach', Role::class));
  }

  public function testShouldCanDetachRoleWhenHavePermissionDetachRoleToUser()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DETACH_ROLE_TO_USER
    ]));

    $this->assertTrue($user->can('detach', Role::class));
  }

  public function testShouldCanDeleteRoleWhenHavePermissionDeleteRole()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_ROLE
    ]));

    $this->assertTrue($user->can('delete', Role::class));
  }

  public function testShouldCanCreateRoleWhenHavePermissionStoreRole()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_ROLE
    ]));

    $this->assertTrue($user->can('create', Role::class));
  }
}
