<?php


namespace Tests\Feature\Http\Controllers\Product;

use App\Http\Controllers\Product\ChangeImageController;
use App\Http\Requests\ImageUpdateRequest;
use App\Product;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

final class ChangeImageControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldUpdateProductImage()
  {
    Storage::fake();

    $user = factory(User::class)->state('admin')->create();
    /** @var Product $product */
    $product = factory(Product::class)->create();

    $file = UploadedFile::fake()->image('image.png');

    $response = $this->actingAs($user)->postJson(route('products.image.update', [
      'product' => $product->id
    ]), [
      'image' => $file
    ]);

    $imageLocation = Product::IMAGES_STORAGE_KEY . '/' . $product->id;

    Storage::assertExists($imageLocation);

    $this->assertEquals($file->get(), Storage::get($imageLocation));

    $response->assertNoContent();
  }

  public function testAssertUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ChangeImageController::class,
      '__invoke',
      'can:update,App\Product'
    );
  }

  public function testAssertUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ChangeImageController::class,
      '__invoke',
      ImageUpdateRequest::class
    );
  }
}
