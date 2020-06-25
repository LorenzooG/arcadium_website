<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('forgot_password', 'Auth\ForgotPasswordController')->name('user.forgot.password');
Route::post('reset_password', 'Auth\ResetPasswordController')->name('user.reset.password');

Route::post('verify_email/{email}', 'Auth\VerifyEmailController')->middleware('signed')->name('user.verify.email');
Route::post('resend/verify_email', 'Auth\ResendVerifyEmailNotificationController')->middleware('auth:api')->name('user.resend.verify.email.notification');

Route::prefix('user')->name('user.')->middleware('auth:api')->group(function () {
  Route::delete('/', 'SelfUserController@delete')->middleware('can:deleteSelf,App\User')->name('delete');

  Route::get('posts', 'SelfUserController@posts')->name('posts.index');
  Route::get('roles', 'SelfUserController@roles')->middleware('can:viewSelf,App\Role')->name('roles.index');

  Route::middleware(['xss', 'throttle:3,1'])->group(function () {
    Route::post('posts', 'PostsController@store')->middleware('can:create,App\Post')->name('posts.store');
    Route::delete('posts/{post}', 'PostsController@delete')->middleware('can:delete,post')->name('posts.delete');

    Route::middleware('can:updateSelf,App\User')->group(function () {
      Route::put('/', 'SelfUserController@update')->name('update');
      Route::post('avatar', 'Auth\ChangeAvatarController')->name('update.avatar');
      Route::put('password', 'Auth\ChangePasswordController')->name('update.password');
      Route::post('update_email', 'Auth\ResetEmailController')->name('request.update.email');
      Route::put('update_email', 'Auth\ChangeEmailController')->name('update.email');
    });
  });
});

Route::prefix('roles')->group(function () {
  Route::get('/', 'RolesController@index')->middleware('can:viewAny,App\Role')->name('roles.index');

  Route::get('{role}', 'RolesController@show')->middleware('can:view,App\Role')->name('roles.show');

  Route::middleware('throttle:10,1')->group(function () {
    Route::post('{role}/attach/{user}', 'RolesController@attach')->middleware('can:attach,App\Role')->name('roles.attach');
    Route::post('{role}/detach/{user}', 'RolesController@detach')->middleware('can:detach,App\Role')->name('roles.detach');

    Route::delete('{role}', 'RolesController@delete')->middleware('can:delete,role')->name('roles.delete');

    Route::middleware('xss')->group(function () {
      Route::post('/', 'RolesController@store')->middleware('can:create,App\Role')->name('roles.store');
      Route::put('{role}', 'RolesController@update')->middleware('can:update,App\Role')->name('roles.update');
    });
  });
});

Route::prefix('news')->name('news.')->group(function () {
  Route::get('/', 'NewsController@index')->name('index');
  Route::get('/{news}', 'NewsController@show')->name('show');

  Route::middleware(['xss', 'throttle:10,1'])->group(function () {
    Route::post('/', 'NewsController@store')->middleware('can:create,App\News')->name('store');
    Route::put('/{news}', 'NewsController@update')->middleware('can:update,App\News')->name('update');
    Route::delete('/{news}', 'NewsController@delete')->middleware('can:delete,App\News')->name('delete');
  });
});

Route::prefix('trashed/users')->name('trashed.users.')->group(function () {
  Route::get('/', 'UsersController@trashed')->middleware('can:viewTrashed,App\User')->name('index');
});

Route::prefix('trashed/products')->name('trashed.products.')->group(function () {
  Route::get('/', 'ProductsController@trashed')->middleware('can:viewTrashed,App\Product')->name('index');
});

Route::prefix('users')->group(function () {
  Route::get('/', 'UsersController@index')->name('users.index');
  Route::get('{user}', 'UsersController@show')->name('users.show');

  Route::get('{user}/roles', 'RolesController@user')->middleware('can:viewAny,App\Role')->name('users.roles.index');
  Route::get('{user}/posts', 'PostsController@user')->name('users.posts.index');

  Route::post('{user}/avatar', 'Auth\ChangeAvatarController')->middleware('can:update,App\User')->name('users.update.avatar');
  Route::get('{user}/avatar', 'UsersController@image')->name('users.avatar');

  Route::middleware('xss')->group(function () {
    Route::post('/', 'UsersController@store')->middleware('throttle:1,5')->name('users.store');

    Route::put('{user}', 'UsersController@update')->middleware('can:update,App\User')->name('users.update');
    Route::delete('{user}', 'UsersController@delete')->middleware('can:delete,App\User')->name('users.delete');
  });

  Route::post('{user}/restore', 'UsersController@restore')->middleware('can:restore,App\User')->name('users.restore');
});

Route::prefix('posts')->name('posts.')->group(function () {
  Route::get('/', 'PostsController@index')->name('index');
  Route::post('/', 'PostsController@store')->middleware(['xss', 'can:create,App\Post'])->name('store');

  Route::prefix('{post}')->group(function () {
    Route::get('/', 'PostsController@show')->name('show');

    Route::middleware('auth:api')->group(function () {
      Route::delete('/', 'PostsController@delete')->middleware('can:delete,post')->name('delete');
      Route::put('/', 'PostsController@update')->middleware(['xss', 'can:update,post'])->name('update');
      Route::post('like', 'PostsController@like')->middleware('can:like,post')->name('like');
      Route::post('unlike', 'PostsController@unlike')->middleware('can:unlike,post')->name('unlike');
    });

    Route::middleware(['xss', 'can:create,App\Comment', 'throttle:1,5'])->group(function () {
      Route::post('comments', 'CommentController@store')->name('comments.store');
    });

    Route::get('comments', 'CommentController@post')->name('comments.index');
  });
});

Route::prefix('comments')->name('comments.')->group(function () {
  Route::middleware(['xss', 'can:update,comment', 'throttle:1,1'])->group(function () {
    Route::put('{comment}', 'CommentController@update')->name('update');
  });

  Route::delete('{comment}', 'CommentController@delete')->middleware('can:delete,comment')->name('delete');
});

Route::prefix('/payments')->name('payments.')->group(function () {
  Route::get('/', 'PaymentsController@index')->middleware('can:view,App\Payment')->name('index');

  Route::get('{payment}', 'PaymentsController@show')->middleware('can:view,App\Payment')->name('show');
  Route::get('{payment}/products', 'PaymentsController@products')->middleware('can:view,App\Payment')->name('products');

  Route::middleware('throttle:1,15')->group(function () {
    Route::post('/{paymentHandler}/', 'PaymentsController@payment')->middleware('can:checkout,App\Payment')->name('checkout');
  });

  Route::post('/{paymentHandler}/notifications', 'PaymentsController@notification')->name('notifications');
});

Route::prefix('punishments')->name('punishments.')->group(function () {
  Route::get('/', 'PunishmentsController@index')->name('index');

  Route::post('/', 'PunishmentsController@store')->middleware(['xss', 'can:create,App\Punishment'])->name('store');

  Route::prefix('{punishment}')->group(function () {
    Route::get('/', 'PunishmentsController@show')->name('show');
    Route::put('/', 'PunishmentsController@update')->middleware(['xss', 'can:update,App\Punishment'])->name('update');
    Route::delete('/', 'PunishmentsController@delete')->middleware('can:delete,App\Punishment')->name('delete');
  });
});

Route::prefix('product_commands')->name('product_commands.')->group(function () {
  Route::put('{command}', 'CommandsController@update')->middleware(['xss', 'can:update,App\ProductCommand'])->name('update');
  Route::delete('{command}', 'CommandsController@delete')->middleware('can:delete,App\ProductCommand')->name('delete');
});

Route::get('staffs', 'StaffController@index')->name('staffs.index');

Route::prefix('products')->name('products.')->group(function () {
  Route::get('/', 'ProductsController@index')->name('index');

  Route::middleware('xss')->group(function () {
    Route::post('/', 'ProductsController@store')->middleware('can:create,App\Product')->name('store');
    Route::put('{product}', 'ProductsController@update')->middleware('can:update,App\Product')->name('update');
  });

  Route::prefix('{product}')->group(function () {
    Route::get('/', 'ProductsController@show')->name('show');
    Route::post('/restore', 'ProductsController@restore')->middleware('can:restore,App\Product')->name('restore');
    Route::delete('/', 'ProductsController@delete')->middleware('can:delete,App\Product')->name('delete');

    Route::prefix('image')->group(function () {
      Route::get('/', 'ProductsController@image')->name('image');
      Route::post('/', 'Product\ChangeImageController')->middleware('can:update,App\Product')->name('image.update');
    });

    Route::prefix('commands')->name('commands.')->group(function () {
      Route::get('/', 'CommandsController@product')->middleware('can:view,App\ProductCommand')->name('index');
      Route::post('', 'CommandsController@store')->middleware(['xss', 'can:create,App\ProductCommand'])->name('store');
    });
  });
});

Route::get('translations', 'TranslationsController')->name('translations');

Route::prefix('auth')->group(function () {
  Route::post('login', 'Auth\LoginController')->name('login');
});


Route::get('/', fn() => [
  'message' => "Welcome to the " . config('app.name') . " api!"
])->name('index');
