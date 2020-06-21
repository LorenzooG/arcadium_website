<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(Role::class, 10)->create([
      'is_staff' => true
    ])->each(function (Role $role) {
      factory(User::class, 25)->create()->each(function (User $user) use ($role) {
        $user->roles()->save($role);
      });
    });
  }
}
