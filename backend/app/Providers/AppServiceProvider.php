<?php

namespace App\Providers;

use App\Comment;
use App\News;
use App\Observers\CommentObserver;
use App\Observers\NewsObserver;
use App\Observers\PaymentObserver;
use App\Observers\PostObserver;
use App\Observers\ProductObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Payment;
use App\Payment\PaymentService;
use App\Payment\Repositories\BankSlipPaymentRepository as BankSlipPaymentHandler;
use App\Payment\Repositories\MercadoPagoPaymentRepository as MercadoPagoPaymentHandler;
use App\Payment\Repositories\PaypalPaymentRepository as PaypalPaymentHandler;
use App\Post;
use App\Product;
use App\Role;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

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

    $this->app->singleton(PayPalEnvironment::class, function () {
      $clientId = config('app.paypal_client_id');
      $clientSecret = config('app.paypal_client_secret');

      return config('app.env') === 'production'
        ? new ProductionEnvironment($clientId, $clientSecret)
        : new SandboxEnvironment($clientId, $clientSecret);
    });

    JsonResource::withoutWrapping();

    User::observe(UserObserver::class);
    Post::observe(PostObserver::class);
    Role::observe(RoleObserver::class);
    Comment::observe(CommentObserver::class);
    News::observe(NewsObserver::class);
    Product::observe(ProductObserver::class);
    Payment::observe(PaymentObserver::class);
  }
}
