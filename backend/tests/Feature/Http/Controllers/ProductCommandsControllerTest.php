<?php


namespace Tests\Feature\Http\Controllers;


use App\Http\Controllers\CommandsController as ActualProductCommandsController;
use App\Http\Requests\CommandStoreRequest;
use App\Http\Requests\CommandUpdateRequest;
use App\Product;
use App\ProductCommand;
use App\User;
use Illuminate\Support\Collection;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class ProductCommandsControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowProductsCommandsWhenGetProductsCommands()
  {
    $user = factory(User::class)->state('admin')->create();

    /** @var Product $product */
    $product = factory(Product::class)->create();

    factory(ProductCommand::class, 3)->create([
      'product_id' => $product->id
    ]);

    $response = $this->actingAs($user)->getJson(route('products.commands.index', [
      'product' => $product->id
    ]));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make($product->commands()->paginate()->items())->map(function (ProductCommand $command) {
          return [
            'id' => $command->id,
            'command' => $command->command,
            'created_at' => $command->created_at->toISOString(),
            'updated_at' => $command->updated_at->toISOString(),
          ];
        })->toArray()
      ]);
  }

  public function testAssertProductUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductCommandsController::class,
      'product',
      'can:view,App\ProductCommand'
    );
  }


  /**
   * Create
   */
  public function testShouldStoreProductsWhenPostProducts()
  {
    $user = factory(User::class)->state('admin')->create();
    /** @var Product $product */
    $product = factory(Product::class)->create();

    $command = $this->faker->text(72);

    $response = $this->actingAs($user)->postJson(route('products.commands.store', [
      'product' => $product->id
    ]), [
      'command' => $command
    ]);

    $commands = ProductCommand::query()
      ->where('id', $response->json('id'))
      ->where('command', $command)
      ->get();

    /** @var ProductCommand $command */
    $command = $commands->first();

    $this->assertCount(1, $commands);

    $response->assertCreated()
      ->assertJson([
        'id' => $command->id,
        'command' => $command->command,
        'created_at' => $command->created_at->toISOString(),
        'updated_at' => $command->updated_at->toISOString(),
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualProductCommandsController::class,
      'store',
      CommandStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductCommandsController::class,
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
    /** @var Product $product */
    $product = factory(Product::class)->create();
    /** @var ProductCommand $productCommand */
    $productCommand = factory(ProductCommand::class)->create([
      'product_id' => $product->id
    ]);

    $response = $this->actingAs($user)->deleteJson(route('product_commands.delete', [
      'command' => $productCommand->id
    ]));

    $this->assertDeleted($productCommand);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductCommandsController::class,
      'delete',
      'can:delete,App\ProductCommand'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdateProductWhenPutProductsAndHavePermission()
  {
    $user = factory(User::class)->state('admin')->create();
    /** @var Product $product */
    $product = factory(Product::class)->create();
    /** @var ProductCommand $productCommand */
    $productCommand = factory(ProductCommand::class)->create([
      'product_id' => $product->id
    ]);

    $command = $this->faker->text(72);

    $response = $this->actingAs($user)->putJson(route('product_commands.update', [
      'command' => $productCommand->id
    ]), [
      'command' => $command,
    ]);

    $products = ProductCommand::query()
      ->where('id', $productCommand->id)
      ->where('command', $command)
      ->get();

    $this->assertCount(1, $products);

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ActualProductCommandsController::class,
      'update',
      CommandUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ActualProductCommandsController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ActualProductCommandsController::class,
      'update',
      'can:update,App\ProductCommand'
    );
  }
}
