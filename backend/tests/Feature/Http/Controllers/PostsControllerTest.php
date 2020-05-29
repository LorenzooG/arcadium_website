<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PostsController as ActualPostsController;
use App\Http\Requests\PostLikeRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Post;
use App\User;
use App\Utils\Permission;
use JMac\Testing\Traits\AdditionalAssertions;
use Psy\Util\Json;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowPostsOrderedByDescLikesWhenGetPosts()
  {
    /* @var User $user */
    $title = $this->faker->title;
    $description = $this->faker->text;

    $user = factory(User::class)->create();
    /* @var Post $firstPost */
    $firstPost = $user->posts()->create([
      'title' => $title,
      'description' => $description,
    ]);
    $firstPost->likes()->saveMany([$user, $user]);

    /* @var Post $secondPost */
    $secondPost = $user->posts()->create([
      'title' => $title,
      'description' => $description,
    ]);
    $secondPost->likes()->saveMany([$user, $user, $user, $user]);

    $response = $this->getJson(route('posts.index'));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $secondPost->id,
            'title' => $secondPost->title,
            'likes' => $secondPost->likes->count(),
            'created_by' => route('users.show', [
              'user' => $user->id
            ]),
            'updated_at' => $secondPost->updated_at->toISOString(),
            'created_at' => $secondPost->updated_at->toISOString(),
          ],
          [
            'id' => $firstPost->id,
            'title' => $firstPost->title,
            'likes' => $firstPost->likes->count(),
            'created_by' => route('users.show', [
              'user' => $user->id
            ]),
            'updated_at' => $firstPost->updated_at->toISOString(),
            'created_at' => $firstPost->updated_at->toISOString(),
          ]
        ],
      ]);
  }

  public function testShouldShowPostsOrderedByDescIdWhenGetUsersPosts()
  {
    /* @var User $user */
    $title = $this->faker->title;
    $description = $this->faker->text;

    $user = factory(User::class)->create();
    /* @var Post $firstPost */
    $firstPost = $user->posts()->create([
      'title' => $title,
      'description' => $description,
    ]);

    /* @var Post $secondPost */
    $secondPost = $user->posts()->create([
      'title' => $title,
      'description' => $description,
    ]);

    $response = $this->getJson(route('users.posts.index', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $secondPost->id,
            'title' => $secondPost->title,
            'likes' => $secondPost->likes->count(),
            'created_by' => route('users.show', [
              'user' => $user->id
            ]),
            'updated_at' => $secondPost->updated_at->toISOString(),
            'created_at' => $secondPost->updated_at->toISOString(),
          ],
          [
            'id' => $firstPost->id,
            'title' => $firstPost->title,
            'likes' => $firstPost->likes->count(),
            'created_by' => route('users.show', [
              'user' => $user->id
            ]),
            'updated_at' => $firstPost->updated_at->toISOString(),
            'created_at' => $firstPost->updated_at->toISOString(),
          ]
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
        'likes' => $post->likes->count(),
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
  public function testShouldStorePostWhenPostUsersPosts()
  {
    $title = $this->faker->title;
    $description = $this->faker->text;

    /* @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::STORE_POST
    ]);

    $response = $this->actingAs($user)->postJson(route('posts.store'), [
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
        'likes' => $post->likes->count(),
        'created_by' => route('users.show', [
          'user' => $user->id
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

  public function testAssertStoreUsesMiddleware()
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
  public function testShouldDeletePostWhenDeletePosts()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::DELETE_ANY_POST
    ]);
    $post = $user->posts()->create([
      'title' => $this->faker->title,
      'description' => $this->faker->text,
    ]);

    $response = $this->actingAs($user)->deleteJson(route('posts.delete', [
      'post' => $post->id
    ]));

    $this->assertDeleted($post);

    $response->assertNoContent();
  }

  public function testShouldDeletePostWhenDeleteUserPosts()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::DELETE_POST
    ]);
    $post = $user->posts()->create([
      'title' => $this->faker->title,
      'description' => $this->faker->text,
    ]);

    $response = $this->actingAs($user)->deleteJson(route('user.posts.delete', [
      'post' => $post->id,
      'user' => $user->id
    ]));

    $this->assertDeleted($post);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesPermissionMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'delete',
      'can:delete,post'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdatePostWhenPutPosts()
  {
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::UPDATE_POST
    ]);
    $post = $user->posts()->create([
      'title' => $this->faker->title,
      'description' => $this->faker->text,
    ]);

    $title = $this->faker->title;
    $description = $this->faker->text;

    $response = $this->actingAs($user)->putJson(route('posts.update', [
      'post' => $post->id
    ]), [
      'title' => $title,
      'description' => $description,
    ]);

    $users = Post::query()
      ->where('id', $post->id)
      ->where('title', $title)
      ->where('description', $description)
      ->get();

    $this->assertCount(1, $users);

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

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'update',
      'can:update,post'
    );
  }

  public function testShouldLikePostWhenPostPostsLike()
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::LIKE_POST
    ]);
    /** @var Post $post */
    $post = $user->posts()->create([
      'title' => $this->faker->title,
      'description' => $this->faker->text,
    ]);

    $response = $this->actingAs($user)->postJson(route('posts.like', [
      'post' => $post->id
    ]));

    $posts = Post::query()
      ->where('id', $post->id)
      ->get();

    $this->assertCount(1, $posts);

    $post = $posts->first();

    $this->assertEquals(1, $post->likes()->count());

    $response->assertNoContent();
  }

  public function testAssertLikeUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualPostsController::class,
      'like',
      PostLikeRequest::class
    );
  }

  public function testAssertLikeUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'like',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'like',
      'can:like,post'
    );
  }
}
