<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UserController as Controller;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use App\Utils\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class UserController extends TestCase
{
  use AdditionalAssertions;

  public function test_should_delete_user_when_delete_user_when_send_password_and_have_permission()
  {
    $password = $this->faker->password(8, 16);

    $user = factory(User::class)->create([
      'password' => $password
    ]);

    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::DELETE_USER
    ]);

    $response = $this->actingAs($user)->deleteJson(route('user.delete'), [
      "password" => $password
    ]);

    $response->assertNoContent();

    $this->assertSoftDeleted($user);
  }

  public function test_assert_delete_uses_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'delete',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      Controller::class,
      'delete',
      'can:delete_self'
    );
  }

  public function test_assert_delete_uses_form_request()
  {
    $this->assertActionUsesFormRequest(
      Controller::class,
      'delete',
      UserDeleteRequest::class
    );
  }

  public function test_should_update_user_when_put_user_and_have_permission()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::UPDATE_USER
    ]);

    $name = $this->faker->name;
    $user_name = $this->faker->name;

    $response = $this->actingAs($user)->putJson(route('user.update'), [
      'name' => $name,
      'user_name' => $user_name,
    ]);

    $users = User::query()
      ->where('id', $user->id)
      ->where('name', $name)
      ->where('user_name', $user_name)
      ->where('email', $user->email)
      ->where('password', $user->password)
      ->get();

    $response->assertNoContent();

    $this->assertCount(1, $users);
  }

  public function test_assert_update_uses_form_request()
  {
    $this->assertActionUsesFormRequest(
      Controller::class,
      'update',
      UserUpdateRequest::class
    );
  }

  public function test_assert_update_uses_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'update',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      Controller::class,
      'update',
      'can:update_self'
    );
  }

  public function test_should_update_password_when_put_user_and_send_old_password()
  {
    $password = $this->faker->password(8, 16);
    $newPassword = $this->faker->password(8, 16);

    $user = factory(User::class)->create([
      'password' => $password,
    ]);
    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::UPDATE_USER
    ]);

    $response = $this->actingAs($user)->putJson(route('user.update.password'), [
      'new_password' => $newPassword,
      'password' => $password
    ]);

    $users = User::query()
      ->where('id', $user->id)
      ->where('name', $user->name)
      ->where('user_name', $user->user_name)
      ->where('email', $user->email)
      ->get();

    $user = $users->first();

    $this->assertTrue(Hash::check($newPassword, $user->password));

    $this->assertCount(1, $users);

    $response->assertNoContent();
  }

  public function test_assert_update_password_uses_form_request()
  {
    $this->assertActionUsesFormRequest(
      Controller::class,
      'updatePassword',
      UserUpdatePasswordRequest::class
    );
  }

  public function test_assert_update_password_uses_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'updatePassword',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      Controller::class,
      'updatePassword',
      'can:update_self'
    );
  }

  public function test_should_update_email_user_when_put_user_email_and_send_token()
  {
    $user = factory(User::class)->create();
    $token = Str::random(64);
    $user->emailUpdates()->create([
      'token' => $token,
      'origin_address' => '127.0.0.1'
    ]);
    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::UPDATE_USER
    ]);

    $email = $this->faker->unique()->safeEmail;

    $response = $this->actingAs($user)->putJson(route('user.update.email', [
      'email_update' => $token
    ]), [
      'new_email' => $email
    ]);

    $users = User::query()
      ->where('id', $user->id)
      ->where('name', $user->name)
      ->where('user_name', $user->user_name)
      ->where('email', $email)
      ->where('password', $user->password)
      ->get();

    $response->assertNoContent();

    $this->assertCount(1, $users);
  }

  public function test_assert_update_email_uses_form_request()
  {
    $this->assertActionUsesFormRequest(
      Controller::class,
      'updateEmail',
      UserUpdateEmailRequest::class
    );
  }

  public function test_assert_update_email_uses_middleware()
  {
    $this->assertActionUsesMiddleware(
      Controller::class,
      'updateEmail',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      Controller::class,
      'updateEmail',
      'can:update_self'
    );
  }
}
