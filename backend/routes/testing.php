<?php

use App\User;
use Illuminate\Contracts\Foundation\Application;
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
  Route::get('password.reset', function (Application $app) {
    $token = Str::random(72);
    $user = factory(User::class)->make();

    /** @var Markdown $markdown */
    $markdown = $app->make(Markdown::class);

    return $markdown->render('notifications.user.password_reset', [
      'token' => $token,
      'user' => $user
    ])->toHtml();
  });

  Route::get('password.reseted', function (Application $app) {
    /** @var Markdown $markdown */
    $markdown = $app->make(Markdown::class);

    return $markdown->render('notifications.user.password_reseted', [
      'user' => factory(User::class)->make()
    ])->toHtml();
  });
});
