<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\NewsController as ActualNewsController;
use App\Http\Requests\NewsStoreRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\News;
use App\User;
use Illuminate\Support\Collection;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowNewsOrderedByDescIdWhenGetNews()
  {
    factory(News::class, 3)->create();

    $response = $this->getJson(route('news.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(News::query()->orderByDesc('id')->paginate()->items())->map(function (News $news) {
          return [
            'id' => $news->id,
            'title' => $news->title,
            'description' => $news->description,
            'created_at' => $news->created_at->toISOString(),
            'updated_at' => $news->updated_at->toISOString(),
          ];
        })->toArray(),
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnPostWhenGetUsersPosts()
  {
    /** @var News $news */
    $news = factory(News::class)->create();

    $response = $this->getJson(route('news.show', [
      'news' => $news->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $news->id,
        'title' => $news->title,
        'description' => $news->description,
        'created_at' => $news->created_at->toISOString(),
        'updated_at' => $news->updated_at->toISOString(),
      ]);
  }

  /**
   * Create
   */
  public function testShouldStorePostWhenPostUsersPosts()
  {
    $title = $this->faker->text(72);
    $description = $this->faker->text(100);

    /* @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('news.store'), [
      'title' => $title,
      'description' => $description
    ]);

    $allNews = News::query()
      ->where('id', $response->json('id'))
      ->where('title', $title)
      ->where('description', $description)
      ->get();

    /* @var News $news */
    $news = $allNews->first();

    $this->assertCount(1, $allNews);

    $response->assertCreated()
      ->assertJson([
        'id' => $news->id,
        'title' => $news->title,
        'description' => $news->description,
        'created_at' => $news->created_at->toISOString(),
        'updated_at' => $news->updated_at->toISOString(),
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualNewsController::class,
      'store',
      NewsStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualNewsController::class,
      'store',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualNewsController::class,
      'store',
      'can:create,App\News'
    );
  }

  /**
   * Delete
   */
  public function testShouldDeletePostWhenDeletePosts()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var News $news */
    $news = factory(News::class)->create();

    $response = $this->actingAs($user)->deleteJson(route('news.delete', [
      'news' => $news->id
    ]));

    $this->assertDeleted($news);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesPermissionMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualNewsController::class,
      'delete',
      'can:delete,App\News'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdatePostWhenPutPosts()
  {
    $title = $this->faker->text(72);
    $description = $this->faker->text(100);

    /* @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var News $news */
    $news = factory(News::class)->create();

    $response = $this->actingAs($user)->putJson(route('news.update', [
      'news' => $news->id
    ]), [
      'title' => $title,
      'description' => $description
    ]);

    $allNews = News::query()
      ->where('id', $news->id)
      ->where('title', $title)
      ->where('description', $description)
      ->get();

    $this->assertCount(1, $allNews);

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualNewsController::class,
      'update',
      NewsUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualNewsController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualNewsController::class,
      'update',
      'can:update,App\News'
    );
  }
}
