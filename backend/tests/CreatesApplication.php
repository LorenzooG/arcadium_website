<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Mocks\ThrottleRequestsMock;

trait CreatesApplication
{
  /**
   * Creates the application.
   *
   * @return Application
   */
  public function createApplication()
  {
    /** @var Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';

    $app->singleton(\App\Http\Kernel::class, function (Application $app) {
      $kernel = new \App\Http\Kernel($app, $app->make(Router::class));

      $kernel->routeMiddleware['throttle'] = ThrottleRequestsMock::class;

      foreach ($kernel->middlewareGroups['api'] as $key => $middleware) {
        if (!Str::contains('throttle', $middleware)) continue;

        $kernel->middlewareGroups['api'][$key] = 'throttle:1000,1';
      }

      return $kernel;
    });

    $app->make(Kernel::class)->bootstrap();

    Storage::fake();
    Notification::fake();

    return $app;
  }
}
