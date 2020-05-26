<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UsersController as Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class UsersController extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function test_should_show_users_and_do_not_show_the_users_emails_when_get_users()
  {
    $user = factory(User::class)->create();

    $response = $this->getJson(route('users.index'));

    $users = $response->json();

    $this->assertCount(1, $users);
    /**
     * @var Carbon $date
     */


    $response->assertOk()
      ->assertJsonPath('0', [
        'id' => $user->id,
        'user_name' => $user->user_name,
        'name' => $user->name,
        'deleted_at' => null,
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
      ])
      ->assertJsonMissing([
        'password',
        'email'
      ]);
  }

  public function test_should_show_users_and_its_emails_when_get_users_and_has_administrator_role()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::VIEW_USER_EMAIL
    ]);

    $response = $this->actingAs($user)->getJson(route('users.index'));

    $users = $response->json();

    $this->assertCount(1, $users);

    $response->assertOk()
      ->assertJsonPath('0', [
        'id' => $user->id,
        'email' => $user->email,
        'user_name' => $user->user_name,
        'name' => $user->name,
        'deleted_at' => null,
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
      ])
      ->assertJsonMissing([
        'password',
      ]);
  }

  /**
   * Read one
   */
  public function test_should_show_an_user_and_do_not_show_its_email_when_get_users()
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

  public function test_should_show_an_user_and_do_not_show_its_email_when_get_users_and_has_administrator_role()
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
  public function test_should_store_user_when_post_users()
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

  public function test_assert_store_uses_form_request()
  {
    $this->assertActionUsesFormRequest(
      Controller::class,
      'store',
      UserStoreRequest::class
    );
  }

  public function test_assert_store_uses_xss_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'store',
      'xss'
    );
  }

  /**
   * Delete
   */
  public function test_should_delete_user_when_delete_users_and_has_administrator_role()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::DELETE_USER
    ]);

    $response = $this->actingAs($user)->deleteJson(route('users.delete', [
      "user" => $user->id
    ]));

    $this->assertSoftDeleted($user);

    $response->assertNoContent();
  }

  public function test_assert_delete_uses_permission_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'delete',
      'can:delete,user'
    );
  }

  /**
   * Update
   */
  public function test_should_update_user_when_put_users_and_has_administrator_role()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::UPDATE_USER
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

  public function test_assert_update_uses_form_request()
  {
    $this->assertActionUsesFormRequest(
      Controller::class,
      'update',
      UserUpdateRequest::class
    );
  }

  public function test_assert_update_uses_permission_and_xss_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      Controller::class,
      'update',
      'can:update,user'
    );
  }
}
