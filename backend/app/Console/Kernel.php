<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    //
  ];

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected final function commands()
  {
    $this->load(__DIR__ . '/Commands');

    /** @noinspection PhpIncludeInspection */
    require base_path('routes/console.php');
  }
}
