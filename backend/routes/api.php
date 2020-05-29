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
  Route::delete('/', 'UserController@delete')->middleware('can:delete_self')->name('user.delete');

  Route::get('posts', 'UserController@posts')->name('user.posts.index');
  Route::get('roles', 'UserController@roles')->middleware('can:viewSelf,App\Role')->name('user.roles.index');

  Route::middleware('xss')->group(function () {
    Route::post('posts', 'PostsController@store')->middleware('can:create,App\Post')->name('posts.store');
    Route::delete('posts/{post}', 'PostsController@delete')->middleware('can:delete,post')->name('user.posts.delete');

    Route::middleware('can:update_self')->group(function () {
      Route::put('/', 'UserController@update')->name('user.update');
      Route::put('/password', 'UserController@updatePassword')->name('user.update.password');
      Route::put('/email/{email_update}', 'UserController@updateEmail')->name('user.update.email');
    });
  });
});

Route::prefix('roles')->group(function () {
  Route::get('/', 'RolesController@index')->middleware('can:viewAny,App\Role')->name('roles.index');
  Route::delete('{role}', 'RolesController@delete')->middleware('can:delete,role')->name('roles.delete');

  Route::middleware('xss')->group(function () {
    Route::get('/', 'RolesController@store')->middleware('can:create,App\Role')->name('roles.store');
    Route::get('{role}', 'RolesController@update')->middleware('can:update,role')->name('roles.update');
  });
});

Route::prefix('users')->group(function () {
  Route::get('/', 'UsersController@index')->name('users.index');
  Route::get('{user}', 'UsersController@show')->name('users.show');

  Route::get('{user}/roles', 'RolesController@user')->middleware('can:view,role')->name('users.roles.show');

  Route::post('{user}/roles/{role}')->middleware('can:attach,App\Role')->name('users.role.attach');
  Route::delete('{role}/roles/{role}')->middleware('can:detach,App\Role')->name('users.role.detach');

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


Route::prefix("products")->name('products.')->group(function () {
  Route::get("/", "ProductsController@index")->name('index');
  Route::get("{product}", "ProductsController@show")->name('show');
  Route::get("{product}/image", "ProductsController@image")->name('image');

  Route::middleware(AdminOnly::class)->group(function () {
    Route::get('{product}/commands', 'CommandsController@index')->name('commands.index');
    Route::delete("{product}", "ProductsController@delete")->name('delete');

    Route::middleware('xss')->group(function () {
      Route::post("/", "ProductsController@store")->name('store');
      Route::post("{product}", "ProductsController@update")->name('update'); // this use post cause' needs FormData request
      Route::post('{product}/commands', 'CommandsController@store')->name('commands.store');
      Route::put('commands/{command}', 'CommandsController@update')->name('commands.update');
    });
  });
});

Route::prefix("auth")->group(function () {
  Route::post("login", "AuthController@login")->name('login');
});
