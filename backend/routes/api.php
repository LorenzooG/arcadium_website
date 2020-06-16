<?php

use App\Http\Middleware\AdminOnly;
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

Route::prefix('user')->middleware('auth:api')->group(function () {
  Route::delete('/', 'SelfUserController@delete')->middleware('can:delete_self')->name('user.delete');

  Route::get('posts', 'SelfUserController@posts')->name('user.posts.index');
  Route::get('roles', 'SelfUserController@roles')->middleware('can:viewSelf,App\Role')->name('user.roles.index');

  Route::middleware('xss')->group(function () {
    Route::post('posts', 'PostsController@store')->middleware('can:create,App\Post')->name('posts.store');
    Route::delete('posts/{post}', 'PostsController@delete')->middleware('can:delete,post')->name('user.posts.delete');

    Route::middleware('can:update_self')->group(function () {
      Route::put('/', 'SelfUserController@update')->name('user.update');
      Route::put('/password', 'SelfUserController@updatePassword')->name('user.update.password');
      Route::put('/email/{email_update}', 'SelfUserController@updateEmail')->name('user.update.email');
    });
  });
});

Route::prefix('roles')->group(function () {
  Route::get('/', 'RolesController@index')->middleware('can:viewAny,App\Role')->name('roles.index');
  Route::delete('{role}', 'RolesController@delete')->middleware('can:delete,role')->name('roles.delete');
	Route::get('{role}', 'RolesController@show')->middleware('can:view,App\Role')->name('roles.show');

  Route::post('{role}/attach/{user}')->middleware('can:attach,App\Role')->name('roles.attach');
  Route::post('{role}/dettach/{user}')->middleware('can:detach,App\Role')->name('roles.detach');

	Route::middleware('xss')->group(function () {
    Route::post('/', 'RolesController@store')->middleware('can:create,App\Role')->name('roles.store');
    Route::put('{role}', 'RolesController@update')->middleware('can:update,App\Role')->name('roles.update');
  });
});

Route::prefix('users')->group(function () {
  Route::get('/', 'UsersController@index')->name('users.index');
  Route::get('{user}', 'UsersController@show')->name('users.show');

  Route::get('{user}/roles', 'RolesController@user')->middleware('can:viewAny,App\Role')->name('users.roles.index');

  Route::get('{user}/posts', 'PostsController@user')->name('users.posts.index');

  Route::middleware('xss')->group(function () {
    Route::post("/", "UsersController@store")->name('users.store');

    Route::put("{user}", "UsersController@update")->middleware('can:update,App\User')->name('users.update');
    Route::delete("{user}", "UsersController@delete")->middleware('can:delete,App\User')->name('users.delete');
  });
});

Route::prefix('posts')->group(function () {
  Route::get('/', 'PostsController@index')->name('posts.index');
  Route::get('{post}', 'PostsController@show')->name('posts.show');
  Route::put('{post}', 'PostsController@update')->middleware(['xss', 'can:update,post'])->name('posts.update');
  Route::post('{post}/like', 'PostsController@like')->middleware(['auth:api', 'can:like,post'])->name('posts.like');
  Route::delete('{post}', 'PostsController@delete')->middleware('can:delete,post')->name('posts.delete');
  Route::post('{post}/comments', 'CommentController@store')->middleware(['can:create,App\Comment', 'xss'])->name('posts.comments.store');
	Route::get('{post}/comments', 'CommentController@post')->name('posts.comments.index');
});

Route::prefix('comments')->group(function () {
  Route::put('{comment}', 'CommentController@update')->middleware(['can:update,comment', 'xss'])->name('comments.update');
  Route::delete('{comment}', 'CommentController@delete')->middleware('can:delete,comment')->name('comments.delete');
});

Route::prefix("/payments")
  ->middleware(AdminOnly::class)
  ->group(function () {
    Route::get("/", "PaymentsController@index")->name('payments.index');
    Route::get("{payment}", "PaymentsController@show")->name('payments.show');
  });

Route::prefix('checkout')->group(function () {
  Route::middleware("xss")->post("/", "PaymentsController@checkout")->name('checkout');
  Route::post("ipn/mp", "PaymentsController@ipn")->name("ipn");
});


Route::prefix('products')->name('products.')->group(function () {
  Route::get('/', 'ProductsController@index')->name('index');

  Route::middleware('xss')->group(function () {
    Route::post('/', 'ProductsController@store')->middleware('can:create,App\Product')->name('store');
    Route::put('{product}', 'ProductsController@update')->middleware('can:update,App\Product')->name('update');
  });

  Route::prefix('{product}')->group(function () {
    Route::get("/", "ProductsController@show")->name('show');
    Route::delete('/', 'ProductsController@delete')->middleware('can:delete,App\Product')->name('delete');

    Route::prefix('image')->name('image.')->group(function () {
      Route::get('/', 'ProductsController@image')->name('show');
      Route::post('/', 'ProductsController@updateImage')->middleware('can:update,App\Product')->name('update');
    });

    Route::prefix('commands')->name('commands.')->group(function () {
      Route::get('/', 'CommandsController@index')->middleware('can:viewAny,App\ProductCommand')->name('index');

      Route::middleware('xss')->group(function () {
        Route::put('{command}', 'CommandsController@update')->middleware('can:update,App\ProductCommand')->name('update');
        Route::post('{command}', 'CommandsController@store')->middleware('can:store,App\ProductCommand')->name('store');
      });
    });
  });
});

Route::prefix("auth")->group(function () {
  Route::post("login", "AuthController@login")->name('login');
});
