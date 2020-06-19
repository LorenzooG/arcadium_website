<?php

/** @var Factory $factory */

use App\Role;
use App\Utils\Permission;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Role::class, function (Faker $faker) {
  return [
    'title' => $faker->text(32),
    'color' => $faker->hexColor,
    'permission_level' => Permission::ALL,
    'is_staff' => false
  ];
});
