<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\SelfUserController as ActualUserController;
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

class SelfUserControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldDeleteUserWhenDeleteUserAndHavePermission()
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

  public function testAssertDeleteUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'delete',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'delete',
      'can:delete_self'
    );
  }

  public function testAssertDeleteUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualUserController::class,
      'delete',
      UserDeleteRequest::class
    );
  }

  public function testShouldUpdateUserWhenPutUserAndHavePermission()
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

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualUserController::class,
      'update',
      UserUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'update',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'update',
      'can:update_self'
    );
  }

  public function testShouldUpdatePasswordWhenPutUser()
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

    $this->assertTrue(Hash::check($newPassword, $user->password));

    $response->assertNoContent();
  }

  public function testAssertUpdatePasswordUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualUserController::class,
      'updatePassword',
      UserUpdatePasswordRequest::class
    );
  }

  public function testAssertUpdatePasswordUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'updatePassword',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'updatePassword',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'updatePassword',
      'can:update_self'
    );
  }

  public function testShouldUpdateEmailUserWhenPutUserEmail()
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

    $this->assertCount(1, $users);

    $response->assertNoContent();
  }

  public function testAssertUpdateEmailUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualUserController::class,
      'updateEmail',
      UserUpdateEmailRequest::class
    );
  }

  public function testAssertUpdateEmailUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'updateEmail',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'updateEmail',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualUserController::class,
      'updateEmail',
      'can:update_self'
    );
  }
}
