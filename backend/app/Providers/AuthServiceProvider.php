<?php

namespace App\Providers;

use App\Auth\JwtGuard;
use App\Comment;
use App\News;
use App\Payment;
use App\Policies\CommentPolicy;
use App\Policies\NewsPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProductCommandPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PunishmentPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Post;
use App\Product;
use App\ProductCommand;
use App\Punishment;
use App\Repositories\Tokens\JwtRepository;
use App\Role;
use App\User;
use App\Utils\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

/**
 * Class AuthServiceProvider
 *
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    User::class => UserPolicy::class,
    Role::class => RolePolicy::class,
    Post::class => PostPolicy::class,
    Comment::class => CommentPolicy::class,
    Product::class => ProductPolicy::class,
    ProductCommand::class => ProductCommandPolicy::class,
    News::class => NewsPolicy::class,
    Payment::class => PaymentPolicy::class,
    Punishment::class => PunishmentPolicy::class
    // 'App\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    Log::info("Bootstrapping authentication service.");

    $this->registerPolicies();

    $this->app->singleton(JwtRepository::class, function (Application $app) {
      return $app->make(JwtRepository::class, [
        'throttle' => config('auth.jwt.throttle'),
        'secret' => config('auth.jwt.secret'),
        'algos' => config('auth.jwt.algos')
      ]);
    });

    Gate::define('update_self', function (User $user) {
      return $user->hasPermission(Permission::UPDATE_USER);
    });

    Gate::define('delete_self', function (User $user) {
      return $user->hasPermission(Permission::DELETE_USER);
    });

    Auth::extend('jwt', function (Application $app) {
      return $app->make(JwtGuard::class, [
        'secret' => config('auth.jwt.secret'),
        'algos' => config('auth.jwt.algos')
      ]);
    });

    Log::info("Bootstrapped authentication service.");
  }
}
