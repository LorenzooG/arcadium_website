<?php

use App\Product;
use App\User;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Testing routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which.
| Enjoy testing your app!
|
*/

Route::prefix('notifications')->group(function () {
  Route::get('password.reset', function (Markdown $markdown) {
    return $markdown->render('notifications.user.password_reset', [
      'token' => Str::random(72),
      'user' => factory(User::class)->make()
    ])->toHtml();
  });

  Route::get('password.reseted', function (Markdown $markdown) {
    return $markdown->render('notifications.user.password_reseted', [
      'user' => factory(User::class)->make()
    ])->toHtml();
  });

  Route::get('product.purchased', function (Markdown $markdown) {
    return $markdown->render('notifications.product.purchased', [
      'user' => factory(User::class)->make(),
      'products' => factory(Product::class, 15)->make()
    ])->toHtml();
  });

  Route::get('product.paid', function (Markdown $markdown) {
    return $markdown->render('notifications.product.paid', [
      'user' => factory(User::class)->make(),
      'products' => factory(Product::class, 15)->make()->map(function (Product $product) {
        $product->pivot = (object)[
          'amount' => 1
        ];

        return $product;
      })
    ])->toHtml();
  });
});
