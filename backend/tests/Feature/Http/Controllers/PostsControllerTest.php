<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PostsController as ActualPostsController;
use App\Http\Requests\PostLikeRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUnlikeRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Post;
use App\User;
use Illuminate\Support\Collection;
use JMac\Testing\Traits\AdditionalAssertions;
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
    $user = factory(User::class)->create();

    factory(Post::class, 15)->create([
      'user_id' => $user->id
    ])->each(function (Post $post) use ($user) {
      $likes = [];

      for ($index = 0; $index < rand(1, 15); $index++) {
        $likes[] = $user;
      }

      $post->likes()->save($user);
    });

    $response = $this->getJson(route('posts.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(Post::byLikes()->paginate()->items())->map(function (Post $post) {
          return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'likes' => $post->likes->count(),
            'created_by' => [
              'id' => $post->user->id,
              'user_name' => $post->user->user_name,
              'name' => $post->user->name,
              'posts' => route('users.posts.index', [
                'user' => $post->user->id
              ]),
              'roles' => route('users.roles.index', [
                'user' => $post->user->id
              ]),
              'avatar' => route('users.avatar', [
                'user' => $post->user->id
              ]),
              'email_verified_at' => $post->user->email_verified_at->toISOString(),
              'deleted_at' => $post->user->deleted_at ?
                $post->user->deleted_at->toISOString()
                : null,
              'created_at' => $post->user->created_at->toISOString(),
              'updated_at' => $post->user->updated_at->toISOString(),
            ],
            'updated_at' => $post->updated_at->toISOString(),
            'created_at' => $post->updated_at->toISOString(),
          ];
        })->toArray(),
      ]);
  }

  public function testShouldShowPostsOrderedByDescIdWhenGetUsersPosts()
  {
    /* @var User $user */
    $user = factory(User::class)->create();

    factory(Post::class, 15)->create([
      'user_id' => $user->id
    ])->each(function (Post $post) use ($user) {
      $likes = [];

      for ($index = 0; $index < rand(1, 15); $index++) {
        $likes[] = $user;
      }

      $post->likes()->save($user);
    });

    $response = $this->getJson(route('users.posts.index', [
      'user' => $user->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make($user->posts()->orderByDesc('id')->paginate()->items())->map(function (Post $post) {
          return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'likes' => $post->likes->count(),
            'created_by' => [
              'id' => $post->user->id,
              'user_name' => $post->user->user_name,
              'name' => $post->user->name,
              'posts' => route('users.posts.index', [
                'user' => $post->user->id
              ]),
              'roles' => route('users.roles.index', [
                'user' => $post->user->id
              ]),
              'avatar' => route('users.avatar', [
                'user' => $post->user->id
              ]),
              'email_verified_at' => $post->user->email_verified_at->toISOString(),
              'deleted_at' => $post->user->deleted_at ?
                $post->user->deleted_at->toISOString()
                : null,
              'created_at' => $post->user->created_at->toISOString(),
              'updated_at' => $post->user->updated_at->toISOString(),
            ],
            'updated_at' => $post->updated_at->toISOString(),
            'created_at' => $post->updated_at->toISOString(),
          ];
        })->toArray()
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnPostWhenGetUsersPosts()
  {
    /* @var User $user */
    $user = factory(User::class)->create();
    /* @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    $response = $this->getJson(route('posts.show', [
      'post' => $post->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $post->id,
        'title' => $post->title,
        'description' => $post->description,
        'likes' => $post->likes->count(),
        'created_by' => [
          'id' => $post->user->id,
          'user_name' => $post->user->user_name,
          'name' => $post->user->name,
          'posts' => route('users.posts.index', [
            'user' => $post->user->id
          ]),
          'roles' => route('users.roles.index', [
            'user' => $post->user->id
          ]),
          'avatar' => route('users.avatar', [
            'user' => $post->user->id
          ]),
          'email_verified_at' => $post->user->email_verified_at->toISOString(),
          'deleted_at' => $post->user->deleted_at ?
            $post->user->deleted_at->toISOString()
            : null,
          'created_at' => $post->user->created_at->toISOString(),
          'updated_at' => $post->user->updated_at->toISOString(),
        ],
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
    $user = factory(User::class)->state('admin')->create();

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
        'description' => $post->description,
        'likes' => $post->likes->count(),
        'created_by' => [
          'id' => $post->user->id,
          'user_name' => $post->user->user_name,
          'name' => $post->user->name,
          'posts' => route('users.posts.index', [
            'user' => $post->user->id
          ]),
          'roles' => route('users.roles.index', [
            'user' => $post->user->id
          ]),
          'avatar' => route('users.avatar', [
            'user' => $post->user->id
          ]),
          'email_verified_at' => $post->user->email_verified_at->toISOString(),
          'deleted_at' => $post->user->deleted_at ?
            $post->user->deleted_at->toISOString()
            : null,
          'created_at' => $post->user->created_at->toISOString(),
          'updated_at' => $post->user->updated_at->toISOString(),
        ],
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
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->deleteJson(route('posts.delete', [
      'post' => $post->id
    ]));

    $this->assertDeleted($post);

    $response->assertNoContent();
  }

  public function testShouldDeletePostWhenDeleteUserPosts()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
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
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
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
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
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

  /**
   * Unlike
   */
  public function testShouldUnlikePostWhenPostPostsUnlike()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    $post->likes()->save($user);

    $response = $this->actingAs($user)->postJson(route('posts.unlike', [
      'post' => $post->id
    ]));

    $post->refresh();

    $this->assertNull($post->likes()->find($user->id));

    $response->assertNoContent();
  }

  public function testAssertUnlikeUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualPostsController::class,
      'unlike',
      PostUnlikeRequest::class
    );
  }

  public function testAssertUnlikeUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'unlike',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ActualPostsController::class,
      'unlike',
      'can:unlike,post'
    );
  }
}
