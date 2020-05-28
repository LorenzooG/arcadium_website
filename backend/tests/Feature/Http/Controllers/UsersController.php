<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UsersController as ActualUsersController;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use App\Utils\Permission;
use Illuminate\Support\Facades\Hash;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class UsersController extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowUsersAndDoNotShowTheUsersEmailsWhenGetUsers()
  {
    $user = factory(User::class)->create();

    $response = $this->getJson(route('users.index'));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'name' => $user->name,
            'deleted_at' => null,
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
          ]
        ]
      ]);
  }

  public function testShouldShowUsersAndItsEmailsWhenGetUsersAndHavePermission()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::VIEW_USER_EMAIL
    ]);

    $response = $this->actingAs($user)->getJson(route('users.index'));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'name' => $user->name,
            'email' => $user->email,
            'deleted_at' => null,
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
          ]
        ]
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnUserAndDoNotShowItsEmailWhenGetUsers()
  {
    $user = factory(User::class)->create();

    $response = $this->getJson(route('users.show', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $user->id,
        'user_name' => $user->user_name,
        'name' => $user->name,
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
        'deleted_at' => null
      ])
      ->assertJsonMissing([
        'password',
        'email'
      ]);
  }

  public function testShouldShowAnUserAndItsEmailWhenGetUserAndHavePermission()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::VIEW_USER_EMAIL
    ]);

    $response = $this->actingAs($user)->getJson(route('users.show', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $user->id,
        'user_name' => $user->user_name,
        'name' => $user->name,
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
        'email' => $user->email,
        'deleted_at' => null
      ])
      ->assertJsonMissing([
        'password'
      ]);
  }

  /**
   * Create
   */
  public function testShouldStoreUserWhenPostUsers()
  {
    $name = $this->faker->name;
    $user_name = $this->faker->name;
    $email = $this->faker->unique()->safeEmail;
    $password = $this->faker->password(8, 16);

    $response = $this->postJson(route('users.store'), [
      "name" => $name,
      "email" => $email,
      "user_name" => $user_name,
      "password" => $password
    ]);

    $users = User::query()
      ->where('id', $response->json('id'))
      ->where('name', $name)
      ->where('email', $email)
      ->where('user_name', $user_name)
      ->get();

    $user = $users->first();

    $userPassword = $user->password;

    $this->assertCount(1, $users);
    $this->assertTrue(Hash::check($password, $userPassword));

    $response->assertCreated()
      ->assertJson([
        'id' => $user->id,
        'user_name' => $user->user_name,
        'name' => $user->name,
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
        'deleted_at' => null
      ])
      ->assertJsonMissing([
        'password',
        'email'
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualUsersController::class,
      'store',
      UserStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUsersController::class,
      'store',
      'xss'
    );
  }

  /**
   * Delete
   */
  public function testShouldDeleteUserWhenDeleteUsersAndHavePermission()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::DELETE_ANY_USER
    ]);

    $response = $this->actingAs($user)->deleteJson(route('users.delete', [
      "user" => $user->id
    ]));

    $this->assertSoftDeleted($user);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUsersController::class,
      'delete',
      'can:delete,App\User'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdateUserWhenPutUsersAndHavePermission()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::UPDATE_ANY_USER
    ]);

    $name = $this->faker->name;
    $user_name = $this->faker->name;
    $email = $this->faker->unique()->safeEmail;
    $password = $this->faker->unique()->password(8, 16);

    $response = $this->actingAs($user)->putJson(route('users.update', [
      'user' => $user->id
    ]), [
      "name" => $name,
      "email" => $email,
      "user_name" => $user_name,
      "password" => $password,
    ]);

    $users = User::query()
      ->where('name', $name)
      ->where('email', $email)
      ->where('user_name', $user_name)
      ->get();

    $updatedUser = $users->first();

    $this->assertCount(1, $users);
    $this->assertTrue(Hash::check($password, $updatedUser->password));

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualUsersController::class,
      'update',
      UserUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUsersController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualUsersController::class,
      'update',
      'can:update,App\User'
    );
  }
}
