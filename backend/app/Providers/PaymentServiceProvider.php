<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
//    MercadoPago::setClientId(config("app.mp_client_id"));
//    MercadoPago::setClientSecret(config("app.mp_client_secret"));
//    MercadoPago::setAccessToken(config("app.mp_access_token"));
  }
}
