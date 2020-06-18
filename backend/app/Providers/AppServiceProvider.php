<?php

namespace App\Providers;

use App\Comment;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Payment\PaymentService;
use App\Payment\Repositories\BankSlipPaymentRepository as BankSlipPaymentHandler;
use App\Payment\Repositories\MercadoPagoPaymentRepository as MercadoPagoPaymentHandler;
use App\Payment\Repositories\PaypalPaymentRepository as PaypalPaymentHandler;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public final function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public final function boot()
  {
    Log::info("Bootstrapped application.");

    $this->app->singleton(PaymentService::class);

    // Singleton payment handlers
    $this->app->singleton(BankSlipPaymentHandler::class);
    $this->app->singleton(MercadoPagoPaymentHandler::class);
    $this->app->singleton(PaypalPaymentHandler::class);

    JsonResource::withoutWrapping();

    User::observe(UserObserver::class);
    Post::observe(PostObserver::class);
    Role::observe(RoleObserver::class);
    Comment::observe(CommentObserver::class);
  }
}
