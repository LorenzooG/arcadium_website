<?php

namespace Tests\Feature\Http\Controllers;

use App\Comment;
use App\Http\Controllers\CommentController;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Post;
use App\User;
use Illuminate\Support\Collection;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowCommentsWhenGetPostsComments()
  {
    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    factory(Comment::class, 15)->create([
      'post_id' => $post->id,
      'user_id' => $user->id
    ]);

    $response = $this->getJson(route('posts.comments.index', [
      'post' => $post->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make($post->comments()->paginate()->items())->map(function (User $user) {
          /** @var Comment $pivot */
          $pivot = $user->pivot;

          return [
            'id' => $pivot->id,
            'content' => $pivot->content,
            'created_by' => route('users.show', [
              'user' => $pivot->user_id
            ]),
            'updated_at' => $pivot->updated_at->toISOString(),
            'created_at' => $pivot->updated_at->toISOString()
          ];
        })->toArray()
      ]);
  }

  /**
   * Create
   */
  public function testShouldStoreCommentWhenPostPostsComments()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);

    $content = $this->faker->text(140);

    $response = $this->actingAs($user)->postJson(route('posts.comments.store', [
      'post' => $post->id
    ]), [
      'content' => $content
    ]);

    $a = $this->app['router']->getRoutes();

    $comments = Comment::query()
      ->where('id', $response->json('id'))
      ->where('content', $content)
      ->where('user_id', $user->id)
      ->where('post_id', $post->id)
      ->get();

    /** @var Comment $comment */
    $comment = $comments->first();

    $this->assertCount(1, $comments);

    $response->assertCreated()
      ->assertJson([
        'id' => $comment->id,
        'content' => $comment->content,
        'created_by' => route('users.show', [
          'user' => $user->id
        ]),
        'updated_at' => $comment->updated_at->toISOString(),
        'created_at' => $comment->updated_at->toISOString()
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      CommentController::class,
      'store',
      CommentStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      CommentController::class,
      'store',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      CommentController::class,
      'store',
      'can:create,App\Comment'
    );
  }

  /**
   * Delete
   */
  public function testShouldDeleteCommentsWhenDeleteComments()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Post $post */
    $post = factory(Post::class)->create([
      'user_id' => $user->id
    ]);
    /** @var Comment $comment */
    $comment = factory(Comment::class)->create([
      'user_id' => $user->id,
      'post_id' => $post->id,
    ]);

    $response = $this->actingAs($user)->deleteJson(route('comments.delete', [
      'comment' => $comment->id
    ]));

    $this->assertDeleted($comment);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesPermissionMiddleware()
  {
    $this->assertActionUsesMiddleware(
      CommentController::class,
      'delete',
      'can:delete,comment'
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
    /** @var Comment $comment */
    $comment = factory(Comment::class)->create([
      'user_id' => $user->id,
      'post_id' => $post->id
    ]);

    $content = $this->faker->text(140);

    $response = $this->actingAs($user)->putJson(route('comments.update', [
      'comment' => $comment->id
    ]), [
      'content' => $content,
    ]);

    $comments = Comment::query()
      ->where('id', $comment->id)
      ->where('content', $content)
      ->where('updated', true)
      ->get();

    $this->assertCount(1, $comments);

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      CommentController::class,
      'update',
      CommentUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      CommentController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      CommentController::class,
      'update',
      'can:update,comment'
    );
  }

}
