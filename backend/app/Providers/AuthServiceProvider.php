<?php

namespace App\Providers;

use App\Auth\JwtGuard;
use App\Policies\UserPolicy;
use App\User;
use App\Utils\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    User::class => UserPolicy::class
    // 'App\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
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
  }
}
