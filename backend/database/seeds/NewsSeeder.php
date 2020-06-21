<?php

use App\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(News::class, 1500)->create();
  }
}
