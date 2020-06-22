<?php

namespace App\Providers;

use App\EmailUpdate;
use App\Repositories\CommentRepository;
use App\Repositories\NewsRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PostRepository;
use App\Repositories\ProductCommandRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PunishmentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider
 *
 * @package App\Providers
 */
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

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @return void
   */
  public function boot()
  {
    Log::info("Bootstrapping route service.");

    $this->bind('emailUpdate', function ($emailUpdate) {
      return EmailUpdate::query()
        ->where('token', $emailUpdate)
        ->firstOrFail();
    });

    $this->bind('user', function ($user) {
      /** @var UserRepository $userRepository */
      $userRepository = $this->app->make(UserRepository::class);

      return $userRepository->findUserById($user);
    });

    $this->bind('post', function ($post) {
      /** @var PostRepository $postRepository */
      $postRepository = $this->app->make(PostRepository::class);

      return $postRepository->findPostById($post);
    });

    $this->bind('comment', function ($comment) {
      /** @var CommentRepository $commentRepository */
      $commentRepository = $this->app->make(CommentRepository::class);

      return $commentRepository->findCommentById($comment);
    });

    $this->bind('command', function ($command) {
      /** @var ProductCommandRepository $productCommandRepository */
      $productCommandRepository = $this->app->make(ProductCommandRepository::class);

      return $productCommandRepository->findProductCommandById($command);
    });

    $this->bind('role', function ($role) {
      /** @var RoleRepository $roleRepository */
      $roleRepository = $this->app->make(RoleRepository::class);

      return $roleRepository->findRoleById($role);
    });

    $this->bind('payment', function ($payment) {
      /** @var PaymentRepository $paymentRepository */
      $paymentRepository = $this->app->make(PaymentRepository::class);

      return $paymentRepository->findPaymentById($payment);
    });

    $this->bind('news', function ($news) {
      /** @var NewsRepository $newsRepository */
      $newsRepository = $this->app->make(NewsRepository::class);

      return $newsRepository->findNewsById($news);
    });

    $this->bind('product', function ($product) {
      /** @var ProductRepository $productRepository */
      $productRepository = $this->app->make(ProductRepository::class);

      return $productRepository->findProductById($product);
    });

    $this->bind('punishment', function ($punishment) {
      /** @var PunishmentRepository $punishmentRepository */
      $punishmentRepository = $this->app->make(PunishmentRepository::class);

      return $punishmentRepository->findPunishmentById($punishment);
    });

    parent::boot();

    Log::info("Bootstrapped route service.");
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
