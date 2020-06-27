<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\SelfUserController as ActualUserController;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Support\Collection;
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
      'can:deleteSelf,App\User'
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
      'can:updateSelf,App\User'
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
            'description' => str_replace('', Str::substr($post->description, 0, 1000), $post->description),
            'likes' => $post->likes->count(),
            'created_by' => [
              'id' => $post->user->id,
              'name' => $post->user->name,
              'user_name' => $post->user->user_name,
              'avatar' => route('users.avatar', [
                'user' => $post->user->id
              ]),
            ],
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
