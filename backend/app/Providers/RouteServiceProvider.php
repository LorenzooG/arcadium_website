<?php

namespace App\Providers;

use App\EmailUpdate;
use App\Repositories\CommentRepository;
use App\Repositories\NewsRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PostRepository;
use App\Repositories\ProductCommandRepository;
use App\Repositories\ProductRepository;
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

  private UserRepository $userRepository;
  private PostRepository $postRepository;
  private RoleRepository $roleRepository;
  private CommentRepository $commentRepository;
  private ProductCommandRepository $productCommandRepository;
  private PaymentRepository $paymentRepository;
  private NewsRepository $newsRepository;
  private ProductRepository $productRepository;

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @return void
   */
  public function boot()
  {
    Log::info("Bootstrapping route service.");

    $this->postRepository = resolve(PostRepository::class);
    $this->userRepository = resolve(UserRepository::class);
    $this->roleRepository = resolve(RoleRepository::class);
    $this->commentRepository = resolve(CommentRepository::class);
    $this->productCommandRepository = resolve(ProductCommandRepository::class);
    $this->paymentRepository = resolve(PaymentRepository::class);
    $this->newsRepository = resolve(NewsRepository::class);
    $this->productRepository = resolve(ProductRepository::class);

    $this->bind('email_update', function (string $email_update) {
      return EmailUpdate::query()
        ->where('token', $email_update)
        ->firstOrFail();
    });

    $this->bind('user', function ($user) {
      return $this->userRepository->findUserById($user);
    });

    $this->bind('post', function ($post) {
      return $this->postRepository->findPostById($post);
    });

    $this->bind('comment', function ($comment) {
      return $this->commentRepository->findCommentById($comment);
    });

    $this->bind('command', function ($command) {
      return $this->productCommandRepository->findProductCommandById($command);
    });

    $this->bind('role', function ($role) {
      return $this->roleRepository->findRoleById($role);
    });

    $this->bind('payment', function ($payment) {
      return $this->paymentRepository->findPaymentById($payment);
    });

    $this->bind('news', function ($news) {
      return $this->newsRepository->findNewsById($news);
    });

    $this->bind('product', function ($product) {
      return $this->productRepository->findProductById($product);
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
