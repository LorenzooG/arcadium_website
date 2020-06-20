<?php


namespace Tests\Feature\Http\Controllers;


use App\Http\Controllers\ProductsController as ActualProductsController;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Product;
use App\User;
use Illuminate\Http\UploadedFile;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class ProductsControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowProductsAndDoNotShowItsCommandsWhenGetProducts()
  {
    $product = factory(Product::class)->create();

    $response = $this->getJson(route('products.index'));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $product->id,
            'title' => $product->title,
            'price' => $product->price,
            'description' => $product->description,
            'image' => route('products.image.show', [
              'product' => $product->id
            ]),
            'created_at' => $product->created_at->toISOString(),
            'updated_at' => $product->updated_at->toISOString(),
          ]
        ]
      ]);
  }

  public function testShouldShowProductsAndShowItsCommandsWhenGetProductsAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();

    $product = factory(Product::class)->create();

    $response = $this->actingAs($user)->getJson(route('products.index'));

    $response->assertOk()
      ->assertJson([
        'data' => [
          [
            'id' => $product->id,
            'title' => $product->title,
            'price' => $product->price,
            'description' => $product->description,
            'image' => route('products.image.show', [
              'product' => $product->id
            ]),
            'commands' => route('products.commands.index', [
              'product' => $product->id
            ]),
            'created_at' => $product->created_at->toISOString(),
            'updated_at' => $product->updated_at->toISOString(),
          ]
        ]
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowAnProductAndDoNotShowItsCommandsWhenGetProducts()
  {
    $product = factory(Product::class)->create();

    $response = $this->getJson(route('products.show', [
      'product' => $product->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $product->id,
        'title' => $product->title,
        'price' => $product->price,
        'description' => $product->description,
        'image' => route('products.image.show', [
          'product' => $product->id
        ]),
        'created_at' => $product->created_at->toISOString(),
        'updated_at' => $product->updated_at->toISOString(),
      ])
      ->assertJsonMissing([
        'commands',
      ]);
  }

  public function testShouldShowAnProductAndShowItsCommandsWhenGetProductsAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();

    $product = factory(Product::class)->create();

    $response = $this->actingAs($user)->getJson(route('products.show', [
      'product' => $product->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $product->id,
        'title' => $product->title,
        'price' => $product->price,
        'description' => $product->description,
        'image' => route('products.image.show', [
          'product' => $product->id
        ]),
        'commands' => route('products.commands.index', [
          'product' => $product->id
        ]),
        'created_at' => $product->created_at->toISOString(),
        'updated_at' => $product->updated_at->toISOString(),
      ]);
  }

  /**
   * Create
   */
  public function testShouldStoreProductsWhenPostProducts()
  {
    $user = factory(User::class)->state('admin')->create();

    $title = $this->faker->text(72);
    $price = $this->faker->numberBetween(0, 25);
    $description = $this->faker->text(6000);
    $image = UploadedFile::fake()->image('profile.png', 100, 100);

    $response = $this->actingAs($user)->postJson(route('products.store'), [
      'title' => $title,
      'price' => $price,
      'description' => $description,
      'image' => $image
    ]);

    $products = Product::query()
      ->where('id', $response->json('id'))
      ->where('title', $title)
      ->where('description', $description)
      ->where('price', $price)
      ->get();

    $product = $products->first();

    $this->assertCount(1, $products);

    $response->assertCreated()
      ->assertJson([
        'id' => $product->id,
        'title' => $product->title,
        'price' => $product->price,
        'description' => $product->description,
        'image' => route('products.image.show', [
          'product' => $product->id
        ]),
        'commands' => route('products.commands.index', [
          'product' => $product->id
        ]),
        'created_at' => $product->created_at->toISOString(),
        'updated_at' => $product->updated_at->toISOString(),
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualProductsController::class,
      'store',
      ProductStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductsController::class,
      'store',
      'xss'
    );
  }

  /**
   * Delete
   */
  public function testShouldDeleteProductWhenDeleteProductsAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();
    $product = factory(Product::class)->create();

    $response = $this->actingAs($user)->deleteJson(route('products.delete', [
      'product' => $product->id
    ]));

    $product->refresh();

    $this->assertDeleted($product);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductsController::class,
      'delete',
      'can:delete,App\Product'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdateProductWhenPutProductsAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();
    $product = factory(Product::class)->create();

    $title = $this->faker->text(72);
    $price = $this->faker->numberBetween(0, 25);
    $description = $this->faker->text(6000);

    $response = $this->actingAs($user)->putJson(route('products.update', [
      'product' => $product->id
    ]), [
      'title' => $title,
      'price' => $price,
      'description' => $description
    ]);

    $products = Product::query()
      ->where('id', $product->id)
      ->where('title', $title)
      ->where('description', $description)
      ->where('price', $price)
      ->get();

    $this->assertCount(1, $products);

    $response->assertNoContent();
  }

  public function testShouldUpdateProductImageWhenPostProductsImageAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();
    $product = factory(Product::class)->create();

    $file = UploadedFile::fake()->image('profile.png', 100, 100);

    $response = $this->actingAs($user)->postJson(route('products.image.update', [
      'product' => $product->id
    ]), [
      'image' => $file
    ]);

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualProductsController::class,
      'update',
      ProductUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductsController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualProductsController::class,
      'updateImage',
      'can:update,App\Product'
    );

    $this->assertActionUsesMiddleware(
      ActualProductsController::class,
      'update',
      'can:update,App\Product'
    );
  }
}
