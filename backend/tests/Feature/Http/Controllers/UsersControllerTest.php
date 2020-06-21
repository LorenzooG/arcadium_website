<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UsersController as ActualUsersController;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowUsersAndDoNotShowTheUsersEmailsWhenGetUsers()
  {
    factory(User::class, 5)->create();

    $response = $this->getJson(route('users.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(User::query()->paginate()->items())->map(function (User $user) {
          return [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'name' => $user->name,
            'deleted_at' => $user->deleted_at,
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
          ];
        })->toArray()
      ]);
  }

  public function testShouldShowUsersAndItsEmailsWhenGetUsersAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->getJson(route('users.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(User::query()->paginate()->items())->map(function (User $user) {
          return [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'email' => $user->email,
            'name' => $user->name,
            'deleted_at' => $user->deleted_at,
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
          ];
        })->toArray()
      ]);
  }

  /**
   * Trashed
   */
  public function testShouldShowTrashedUsersAndDoNotShowTheUsersEmailsWhenGetUsersTrashed()
  {
    $user = factory(User::class)->state('admin')->create();

    factory(User::class, 5)->create()->map(function (User $user) {
      $user->delete();

      return $user;
    });

    $response = $this->actingAs($user)->getJson(route('trashed.users.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(User::onlyTrashed()->paginate()->items())->map(function (User $user) {
          return [
            'id' => $user->id,
            'email' => $user->email,
            'user_name' => $user->user_name,
            'name' => $user->name,
            'deleted_at' => $user->deleted_at->toISOString(),
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString()
          ];
        })->toArray()
      ]);
  }

  public function testAssertTrashedUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUsersController::class,
      'trashed',
      'can:viewTrashed,App\User'
    );
  }

  /**
   * Read one
   */
  public function testShouldShowAnUserAndDoNotShowItsEmailWhenGetUsers()
  {
    /** @var User $user */
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
        'deleted_at' => $user->deleted_at ?
          $user->deleted_at->toISOString()
          : null
      ])
      ->assertJsonMissing([
        'password',
        'email'
      ]);
  }

  public function testShouldShowAnUserAndItsEmailWhenGetUserAndHavePermission()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->getJson(route('users.show', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $user->id,
        'user_name' => $user->user_name,
        'email' => $user->email,
        'name' => $user->name,
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
        'deleted_at' => $user->deleted_at ?
          $user->deleted_at->toISOString()
          : null
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

    /** @var User $user */
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
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

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
   * Restore
   */
  public function testShouldRestoreUserWhenPostUsersRestoreAndHavePermission()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    /** @var User $dummyUser */
    $dummyUser = factory(User::class)->create();
    $dummyUser->delete();

    $dummyUser->refresh();

    $response = $this->actingAs($user)->postJson(route('users.restore', [
      'user' => $dummyUser->id
    ]));

    $dummyUser->refresh();

    $this->assertFalse($dummyUser->trashed());

    $response->assertNoContent();
  }

  public function testAssertRestoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUsersController::class,
      'restore',
      'can:restore,App\User'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdateUserWhenPutUsersAndHavePermission()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

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
