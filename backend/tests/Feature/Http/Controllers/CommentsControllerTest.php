<?php

namespace Tests\Feature\Http\Controllers;

use App\Comment;
use App\Http\Controllers\CommentController;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\User;
use App\Utils\Permission;
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
    $title = $this->faker->title;
    $description = $this->faker->text;

    $commentContent = $this->faker->text(140);

    $user = factory(User::class)->create();

    $post = $user->posts()->create([
      'title' => $title,
      'description' => $description,
    ]);

    $comment = $post->comments()->create([
      'user_id' => $user->id,
      'post_id' => $post->id,
      'content' => $commentContent
    ]);

    $response = $this->getJson(route('posts.comments.index', [
      'post' => $post->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $comment->id,
            'content' => $comment->content,
            'created_by' => route('users.show', [
              'user' => $user->id
            ]),
            'updated_at' => $comment->updated_at->toISOString(),
            'created_at' => $comment->updated_at->toISOString(),
          ],
        ],
      ]);
  }

  /**
   * Create
   */
  public function testShouldStoreCommentWhenPostPostsComments()
  {
    $title = $this->faker->title;
    $description = $this->faker->text;

    $commentContent = $this->faker->text(140);

    $user = factory(User::class)->create();

    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::STORE_COMMENT
    ]);

    $post = $user->posts()->create([
      'title' => $title,
      'description' => $description
    ]);

    $response = $this->actingAs($user)->postJson(route('posts.comments.store', [
      'post' => $post->id
    ]), [
      'content' => $commentContent
    ]);

    $comments = Comment::query()
      ->where('id', $response->json('id'))
      ->where('content', $commentContent)
      ->where('user_id', $user->id)
      ->where('post_id', $post->id)
      ->get();

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
    $user = factory(User::class)->create();

    $user->roles()->create([
      'title' => 'Administrator',
      'permission_level' => Permission::DELETE_ANY_COMMENT
    ]);

    $post = $user->posts()->create([
      'title' => $this->faker->title,
      'description' => $this->faker->text,
    ]);

    $comment = $post->comments()->create([
      'user_id' => $user->id,
      'post_id' => $post->id,
      'content' => $this->faker->text(140)
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
    $user = factory(User::class)->create();

    $user->roles()->create([
      'title' => 'Permission',
      'permission_level' => Permission::UPDATE_ANY_COMMENT
    ]);

    $post = $user->posts()->create([
      'title' => $this->faker->title,
      'description' => $this->faker->text,
    ]);

    $comment = $post->comments()->create([
      'user_id' => $user->id,
      'post_id' => $post->id,
      'content' => $this->faker->text(140)
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
