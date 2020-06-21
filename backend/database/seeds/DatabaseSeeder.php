<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    Notification::fake();

    $this->call([
      ProductSeeder::class,
      StaffSeeder::class,
      NewsSeeder::class,
      UserSeeder::class,
    ]);
  }
}
