<?php

namespace Tests\Feature\Http\Controllers;

use App\EmailUpdate;
use App\Http\Controllers\SelfUserController as ActualUserController;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Notifications\RequestEmailUpdateNotification;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class SelfUserControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldDeleteUserWhenDeleteUserAndHavePermission()
  {
    $password = $this->faker->password(8, 16);

    $user = factory(User::class)->state('admin')->create([
      'password' => $password
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
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

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

  public function testShouldSendUpdateEmailEmailWhenPostUserUpdateEmail()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('user.request.update.email'), [
      'email' => $user->email,
    ]);

    Notification::assertSentTo($user, RequestEmailUpdateNotification::class);

    $response->assertNoContent();
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

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create([
      'password' => $password,
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
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $token = Str::random(64);

    /** @var EmailUpdate $request */
    $request = factory(EmailUpdate::class)->create([
      'user_id' => $user->id,
      'token' => $token,
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

    $request = EmailUpdate::findOrFail($request->id);

    $this->assertFalse($request->isValid());
    $this->assertEquals(1, $request->already_used);

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

  public function testShouldShowPostsOrderedByDescIdWhenGetUserPosts()
  {
    /* @var User $user */
    $user = factory(User::class)->create();

    factory(Post::class, 5)->create([
      'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->getJson(route('user.posts.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make($user->posts()->orderByDesc('id')->paginate()->items())->map(function (Post $post) {
          return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'likes' => $post->likes->count(),
            'created_by' => route('users.show', [
              'user' => $post->user->id
            ]),
            'updated_at' => $post->updated_at->toISOString(),
            'created_at' => $post->updated_at->toISOString(),
          ];
        })->toArray()
      ]);
  }

  public function testShouldShowRolesWhenGetUserRoles()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->getJson(route('user.roles.index', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make($user->roles()->paginate()->items())->map(function (Role $role) {
          return [
            'id' => $role->id,
            'title' => $role->title,
            'permission_level' => $role->permission_level,
            'color' => $role->color,
            'created_at' => $role->created_at->toISOString(),
            'updated_at' => $role->updated_at->toISOString()
          ];
        })->toArray()
      ]);
  }

}
