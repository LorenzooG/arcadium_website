<?php

use App\Payment;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(User::class, 100)->create()->each(function (User $user) {
      $this->command->getOutput()->writeln("<comment>Generating user {$user->id} data...</comment>");

      factory(Post::class, 160)->create([
        'user_id' => $user->id
      ]);

      factory(Payment::class, 50)->create([
        'user_id' => $user->id
      ]);
    });

    factory(User::class, 10000)->create();
  }
}
