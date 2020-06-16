<?php

namespace App\Providers;

use App\EmailUpdate;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * This namespace is applied to your controller routes.
   *
   * In addition, it is set as the URL generator's root namespace.
   *
   * @var string
   */
  protected $namespace = 'App\Http\Controllers';

  private UserRepository $userRepository;
  private PostRepository $postRepository;
  private RoleRepository $roleRepository;
  private CommentRepository $commentRepository;

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @return void
   */
  public function boot()
  {
    $this->postRepository = resolve(PostRepository::class);
    $this->userRepository = resolve(UserRepository::class);
    $this->roleRepository = resolve(RoleRepository::class);
    $this->commentRepository = resolve(CommentRepository::class);

    $this->bind('email_update', function (string $email_update) {
      return EmailUpdate::query()
        ->where('token', $email_update)
        ->firstOrFail();
    });

    $this->bind('user', function (int $user) {
      return $this->userRepository->findUserById($user);
    });

    $this->bind('post', function (int $post) {
      return $this->postRepository->findPostById($post);
    });

    $this->bind('comment', function (int $comment) {
      return $this->commentRepository->findCommentById($comment);
    });

    $this->bind('role', function (int $role) {
      return $this->roleRepository->findRoleById($role);
    });

    parent::boot();
  }

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map()
  {
    $this->mapApiRoutes();
    $this->mapWebRoutes();
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapWebRoutes()
  {
    Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/web.php'));
  }

  /**
   * Define the "api" routes for the application.
   *
   * These routes are typically stateless.
   *
   * @return void
   */
  protected function mapApiRoutes()
  {
    Route::prefix('api/v1')
      ->middleware('api')
      ->namespace($this->namespace)
      ->group(base_path('routes/api.php'));
  }
}
