<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    $app->make(Kernel::class)->bootstrap();

    /** @var Router $router */
    $router = $app['router'];

    $routes = $router->getRoutes();
    $routes = $routes->getRoutes();

    foreach ($routes as $index => $route) {
      $route->parameters = [];

      $middleware = collect($route->action['middleware'] ?? [])
        ->filter(fn($middleware) => !Str::contains($middleware, 'throttle'));

      $route->action['middleware'] = $middleware->toArray();

      $routes[$index] = $route;
    };

    Storage::fake();
    Notification::fake();

    return $app;
  }
}
