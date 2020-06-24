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
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
      return new JwtRepository(
        $app->make(ConnectionInterface::class),
        config('auth.jwt.secret'),
        config('auth.jwt.algos'),
        config('auth.jwt.hash_algos'),
        config('auth.jwt.expires'),
      );
    });

    Auth::extend('jwt', function (Application $app) {
      return new JwtGuard(
        $app->make(UserRepository::class),
        $app->make(JwtRepository::class),
        $app->make(Hasher::class),
        $app->make(Request::class),
        config('auth.jwt.secret'),
        config('auth.jwt.algos')
      );
    });

    Log::info("Bootstrapped authentication service.");
  }
}
