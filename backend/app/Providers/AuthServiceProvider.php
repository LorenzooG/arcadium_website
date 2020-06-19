<?php

namespace App\Providers;

use App\Auth\JwtGuard;
use App\Comment;
use App\News;
use App\Policies\CommentPolicy;
use App\Policies\NewsPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProductCommandPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Post;
use App\Product;
use App\ProductCommand;
use App\Role;
use App\User;
use App\Utils\Permission;
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
    News::class => NewsPolicy::class
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

    $jwtAuth = new JwtGuard();

    Gate::define('update_self', function (User $user) {
      return $user->hasPermission(Permission::UPDATE_USER);
    });

    Gate::define('delete_self', function (User $user) {
      return $user->hasPermission(Permission::DELETE_USER);
    });

    Auth::extend("jwt", function () use ($jwtAuth) {
      return $jwtAuth;
    });

    Log::info("Bootstrapped authentication service.");
  }
}
