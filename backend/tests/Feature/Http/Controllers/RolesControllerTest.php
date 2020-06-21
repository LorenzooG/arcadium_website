<?php


namespace Tests\Feature\Http\Controllers;


use App\Http\Controllers\RolesController as ActualRolesController;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Role;
use App\User;
use App\Utils\Permission;
use Illuminate\Support\Collection;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class RolesControllerTest extends TestCase
{

  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowRolesWhenGetRoles()
	{
    /* @var User $user */
    $user = factory(User::class)->state('admin')->create();

    factory(Role::class, 5)->create();

    $response = $this->actingAs($user)->getJson(route('roles.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(Role::query()->paginate()->items())->map(function (Role $role) {
          return [
            'id' => $role->id,
            'title' => $role->title,
            'color' => $role->color,
            'permission_level' => $role->permission_level,
            'created_at' => $role->created_at->toISOString(),
            'updated_at' => $role->updated_at->toISOString()
          ];
        })->toArray()
      ]);
  }

  public function testShouldShowRolesWhenGetUsersRoles()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    factory(Role::class, 5)->create();

    $response = $this->actingAs($user)->getJson(route('users.roles.index', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make($user->roles()->paginate()->items())->map(function (Role $role) {
          return [
            'id' => $role->id,
            'title' => $role->title,
            'color' => $role->color,
            'permission_level' => $role->permission_level,
            'created_at' => $role->created_at->toISOString(),
            'updated_at' => $role->updated_at->toISOString()
          ];
        })->toArray()
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnRoleWhenGetRoles()
  {
    /* @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Role $role */
    $role = factory(Role::class)->create();

    $response = $this->actingAs($user)->getJson(route('roles.show', [
      'role' => $role->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $role->id,
        'title' => $role->title,
        'color' => $role->color,
        'permission_level' => $role->permission_level,
        'updated_at' => $role->updated_at->toISOString(),
        'created_at' => $role->updated_at->toISOString(),
      ]);
  }

  /**
   * Create
   */
  public function testShouldStoreRoleWhenPostRoles()
  {
    $title = $this->faker->text(32);
    $color = $this->faker->hexColor;
    $permissionLevel = Permission::ALL;

    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('roles.store'), [
      'title' => $title,
      'color' => $color,
      'permission_level' => $permissionLevel
    ]);

    $roles = Role::query()
      ->where('id', $response->json('id'))
      ->where('title', $title)
      ->where('permission_level', $permissionLevel)
      ->where('color', $color)
      ->get();

    /** @var Role $role */
    $role = $roles->first();

    $this->assertCount(1, $roles);

    $response->assertCreated()
      ->assertJson([
        'id' => $role->id,
        'title' => $role->title,
        'color' => $role->color,
        'permission_level' => $role->permission_level,
        'updated_at' => $role->updated_at->toISOString(),
        'created_at' => $role->updated_at->toISOString(),
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualRolesController::class,
      'store',
      RoleStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'store',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'store',
      'can:create,App\Role'
    );
  }

  /**
   * Delete
   */
  public function testShouldDeleteRoleWhenDeleteRoles()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Role $role */
    $role = factory(Role::class)->create();

    $response = $this->actingAs($user)->deleteJson(route('roles.delete', [
      'role' => $role->id
    ]));

    $this->assertDeleted($role);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesPermissionMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'delete',
      'can:delete,role'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdateRoleWhenPutRoles()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Role $role */
    $role = factory(Role::class)->create();

    $title = $this->faker->title;
    $color = $this->faker->hexColor;
    $permissionLevel = Permission::ALL;

    $response = $this->actingAs($user)->putJson(route('roles.update', [
      'role' => $role->id
    ]), [
      'title' => $title,
      'color' => $color,
      'permission_level' => $permissionLevel,
    ]);

    $roles = Role::query()
      ->where('id', $role->id)
      ->where('title', $title)
      ->where('permission_level', $permissionLevel)
      ->where('color', $color)
      ->get();

    $this->assertCount(1, $roles);

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualRolesController::class,
      'update',
      RoleUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'update',
      'can:update,App\Role'
    );
  }

  /**
   * Attach
   */
  public function testShouldAttachRoleToUserWhenPostRolesAttach()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Role $role */
    $role = factory(Role::class)->create();

    $response = $this->actingAs($user)->postJson(route('roles.attach', [
      'role' => $role->id,
      'user' => $user->id
    ]));

    $role->refresh();

    $this->assertNotNull($role->users()->find($user->id));

    $response->assertNoContent();
  }

  public function testAssertAttachUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'attach',
      'can:attach,App\Role'
    );
  }

  /**
   * Attach
   */
  public function testShouldDetachRoleToUserWhenPostRolesDetach()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Role $role */
    $role = factory(Role::class)->create();

    $user->roles()->save($role);

    $response = $this->actingAs($user)->postJson(route('roles.detach', [
      'role' => $role->id,
      'user' => $user->id
    ]));

    $role->refresh();

    $this->assertNull($role->users()->find($user->id));

    $response->assertNoContent();
  }

  public function testAssertDetachUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualRolesController::class,
      'detach',
      'can:detach,App\Role'
    );
  }
}
