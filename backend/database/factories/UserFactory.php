<?php

/** @var Factory $factory */

use App\Role;
use App\User;
use App\Utils\Permission;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(User::class, function (Faker $faker) {
  return [
    'name' => $faker->name,
    'user_name' => $faker->userName,
    'email' => $faker->unique()->safeEmail,
    'password' => $faker->password(8, 16),
  ];
});

$factory->afterCreatingState(User::class, 'admin', function (User $user, Faker $faker) {
  $role = Role::create([
    'title' => $faker->text(16),
    'color' => $faker->hexColor,
    'permission_level' => Permission::ALL
  ]);

  $user->roles()->save($role);
});
