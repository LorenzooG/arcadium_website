<?php

namespace App\Providers;

use App\Comment;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    JsonResource::withoutWrapping();

    User::observe(UserObserver::class);
    Post::observe(PostObserver::class);
    Role::observe(RoleObserver::class);
    Comment::observe(CommentObserver::class);
  }
}
