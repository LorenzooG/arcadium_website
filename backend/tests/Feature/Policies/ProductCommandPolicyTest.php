<?php


namespace Tests\Feature\Policies;

use App\ProductCommand;
use App\Role;
use App\User;
use App\Utils\Permission;
use Tests\TestCase;

final class ProductCommandPolicyTest extends TestCase
{
  public function testShouldCanUpdateProductCommandWhenHavePermissionUpdateProductCommand()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::UPDATE_PRODUCT_COMMAND
    ]));

    $this->assertTrue($user->can('update', ProductCommand::class));
  }

  public function testShouldCanDeleteProductCommandWhenHavePermissionDeleteProductCommand()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::DELETE_PRODUCT_COMMAND
    ]));

    $this->assertTrue($user->can('delete', ProductCommand::class));
  }

  public function testShouldCanCreateProductCommandWhenHavePermissionStoreProductCommand()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::STORE_PRODUCT_COMMAND
    ]));

    $this->assertTrue($user->can('create', ProductCommand::class));
  }

  public function testShouldCanViewProductCommandWhenHavePermissionViewProductCommands()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->save(factory(Role::class)->create([
      'permission_level' => Permission::VIEW_PRODUCT_COMMANDS
    ]));

    $this->assertTrue($user->can('view', ProductCommand::class));
  }
}
