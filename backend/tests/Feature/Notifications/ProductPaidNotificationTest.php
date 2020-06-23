<?php


namespace Tests\Feature\Notifications;

use App\Notifications\ProductPaidNotification;
use App\Product;
use App\User;
use Tests\TestCase;

final class ProductPaidNotificationTest extends TestCase
{
  public function testShouldRenderProductPaidNotificationCorrectly()
  {
    /** @var User $user */
    $user = factory(User::class)->make();
    $products = factory(Product::class, 15)->make()->map(function (Product $product) {
      $product->pivot = (object)[
        'amount' => 1
      ];

      return $product;
    });

    $notification = new ProductPaidNotification($products);

    $rendered = $notification->toMail($user)->render();

    $this->assertStringContainsString($user->name, $rendered);

    $products->each(function (Product $product) use ($rendered) {
      $this->assertStringContainsString($product->title, $rendered);
      $this->assertStringContainsString($product->pivot->amount, $rendered);
    });
  }

  public function testShouldViaInProductPaidNotificationReturnArrayWithEmail()
  {
    /** @var User $user */
    $user = factory(User::class)->make();
    $products = factory(Product::class, 15)->make();

    $notification = new ProductPaidNotification($products);

    $this->assertEquals(['mail'], $notification->via($user));
  }

  public function testShouldToArrayInProductPaidNotificationReturnArrayWithProductsAndUser()
  {
    /** @var User $user */
    $user = factory(User::class)->make();
    $products = factory(Product::class, 15)->make();

    $notification = new ProductPaidNotification($products);

    $this->assertEquals([
      'user' => $user,
      'products' => $products
    ], $notification->toArray($user));
  }
}
