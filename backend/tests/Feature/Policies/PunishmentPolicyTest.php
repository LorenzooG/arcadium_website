<?php


namespace Tests\Feature\Policies;

use App\Punishment;
use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class PunishmentPolicyTest extends TestCase
{
  public function testShouldCanUpdatePunishmentWhenHavePermissionUpdatePunishment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_PUNISHMENT
    ]));

    $this->assertTrue($user->can('update', Punishment::class));
  }

  public function testShouldCanDeletePunishmentWhenHavePermissionDeletePunishment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_PUNISHMENT
    ]));

    $this->assertTrue($user->can('delete', Punishment::class));
  }

  public function testShouldCanCreatePunishmentWhenHavePermissionStorePunishment()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_PUNISHMENT
    ]));

    $this->assertTrue($user->can('create', Punishment::class));
  }
}
