<?php


namespace Tests\Feature\Http\Controllers;


use App\Http\Controllers\RolesController as ActualRolesController;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Role;
use App\User;
use App\Utils\Permission;
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
    $title = $this->faker->title;
    $permissionLevel = Permission::VIEW_ANY_ROLE | Permission::VIEW_ROLES_PERMISSIONS;
    $color = $this->faker->hexColor;

		$user = factory(User::class)->create();
		$role = $user->roles()->create([
      'title' => $title,
      'color' => $color,
      'permission_level' => $permissionLevel
		]);

    $response = $this->actingAs($user)->getJson(route('roles.index'));

    $response->assertOk()
      ->assertJson([
				'data' => [
          [
            'id' => $role->id,
            'title' => $title,
            'color' => $color,
            'permission_level' => $permissionLevel,
            'created_at' => $role->created_at->toISOString(),
            'updated_at' => $role->updated_at->toISOString()
          ]
				]
      ]);
  }

  public function testShouldShowRolesWhenGetUsersRoles()
  {
    $title = $this->faker->title;
    $permissionLevel = Permission::VIEW_ANY_ROLE | Permission::VIEW_ROLES_PERMISSIONS;
    $color = $this->faker->hexColor;

    $user = factory(User::class)->create();
		$role = $user->roles()->create([
			'title' => $title,
			'permission_level' => $permissionLevel,
			'color' => $color,
		]);

    $response = $this->actingAs($user)->getJson(route('users.roles.index', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $role->id,
            'title' => $title,
            'permission_level' => $permissionLevel,
            'color' => $color,
            'created_at' => $role->created_at->toISOString(),
            'updated_at' => $role->updated_at->toISOString()
          ]
        ]
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnRoleWhenGetRoles()
  {
    /* @var User $user */
    $title = $this->faker->title;
		$color = '#fff';
		$permissionLevel = Permission::VIEW_ROLE | Permission::VIEW_ROLES_PERMISSIONS;

    $user = factory(User::class)->create();
		$role = $user->roles()->create([
			'title' => $title,
			'color' => $color,
			'permission_level' => $permissionLevel
		]);

		$response = $this->actingAs($user)->getJson(route('roles.show', [
      'role' => $role->id
		]));

    $response->assertOk()
      ->assertJson([
				'id' => $role->id,
				'title' => $role->title,
				'color' => $color,
				'permission_level' => $permissionLevel,
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
		$permissionLevel = Permission::STORE_ROLE | Permission::VIEW_ROLES_PERMISSIONS;

    $user = factory(User::class)->create();
    $role = $user->roles()->create([
			'title' => $title,
			'color' => $color,
      'permission_level' => $permissionLevel
    ]);

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

    $role = $roles->first();

    $this->assertCount(1, $roles);

    $response->assertCreated()
      ->assertJson([
        'id' => $role->id,
        'title' => $role->title,
				'color' => $color,
				'permission_level' => $permissionLevel,
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
    $user = factory(User::class)->create();
    $role = $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::DELETE_ROLE
    ]);

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
    $user = factory(User::class)->create();
    $role = $user->roles()->create([
      'title' => $this->faker->title,
      'permission_level' => Permission::UPDATE_ROLE,
      'color' => $this->faker->hexColor
    ]);

    $title = $this->faker->title;
    $color = $this->faker->hexColor;
    $permissionLevel = Permission::NONE;

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
}
