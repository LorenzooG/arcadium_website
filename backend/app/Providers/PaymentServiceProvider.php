<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

/**
 * Class PaymentServiceProvider
 *
 * @package App\Providers
 */
class PaymentServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    Log::info("Bootstrapping payment service.");
//    MercadoPago::setClientId(config("app.mp_client_id"));
//    MercadoPago::setClientSecret(config("app.mp_client_secret"));
//    MercadoPago::setAccessToken(config("app.mp_access_token"));
    Log::info("Bootstrapped payment service.");
  }
}
