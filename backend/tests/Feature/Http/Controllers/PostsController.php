<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PostsController as ActualPostsController;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Post;
use App\User;
use App\Utils\Permission;
use Illuminate\Support\Facades\Hash;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class PostsController extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowUsersPostsWhenGetUsersPosts()
  {
    /* @var User $user */
    $title = $this->faker->title;
    $description = $this->faker->text;

    $user = factory(User::class)->create();
    /* @var Post $post */
    $post = $user->posts()->create([
      'title' => $title,
      'description' => $description
    ]);

    $response = $this->getJson(route('users.posts.index', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        [
          'id' => $post->id,
          'title' => $post->title,
          'likes' => $post->likes,
          'created_by' => route('users.show', [
            'user' => $user->id
          ]),
          'updated_at' => $post->updated_at->toISOString(),
          'created_at' => $post->updated_at->toISOString(),
        ]
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnPostWhenGetUsersPosts()
  {
    /* @var User $user */
    $title = $this->faker->title;
    $description = $this->faker->text;

    $user = factory(User::class)->create();
    /* @var Post $post */
    $post = $user->posts()->create([
      'title' => $title,
      'description' => $description
    ]);

    $response = $this->getJson(route('posts.show', [
      'post' => $post->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $post->id,
        'title' => $post->title,
        'likes' => $post->likes,
        'created_by' => route('users.show', [
          'user' => $user->id
        ]),
        'updated_at' => $post->updated_at->toISOString(),
        'created_at' => $post->updated_at->toISOString(),
      ]);
  }

  /**
   * Create
   */
  public function testShouldStoreUserWhenPostUsersPosts()
  {
    $title = $this->faker->title;
    $description = $this->faker->text;

    /* @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::STORE_POST
    ]);

    $response = $this->actingAs($user)->postJson(route('posts.store', [
      'user' => $user->id
    ]), [
      'title' => $title,
      'description' => $description
    ]);

    $posts = Post::query()
      ->where('id', $response->json('id'))
      ->where('title', $title)
      ->where('description', $description)
      ->get();

    /* @var Post $post */
    $post = $posts->first();

    $this->assertCount(1, $posts);

    $response->assertCreated()
      ->assertJson([
        'id' => $post->id,
        'title' => $post->title,
        'likes' => $post->likes,
        'created_by' => route('users.show', [
          'user' => $post->id
        ]),
        'updated_at' => $post->updated_at->toISOString(),
        'created_at' => $post->updated_at->toISOString(),
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualPostsController::class,
      'store',
      PostStoreRequest::class
    );
  }

  public function testAssertStoreUsesXssMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'store',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'store',
      'can:create,App\Post'
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
      'permission_level' => Permission::DELETE_USER
    ]);

    $response = $this->actingAs($user)->deleteJson(route('users.delete', [
      "user" => $user->id
    ]));

    $this->assertSoftDeleted($user);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesPermissionMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'delete',
      'can:delete,App\Post'
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

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualPostsController::class,
      'update',
      PostUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesPermissionAndXssMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'update',
      'can:update,App\Post'
    );
  }
}
