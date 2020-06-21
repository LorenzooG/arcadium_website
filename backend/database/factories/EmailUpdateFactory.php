<?php

/** @var Factory $factory */

use App\EmailUpdate;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(EmailUpdate::class, function (Faker $faker) {
  return [
    'origin_address' => $faker->ipv4,
    'token' => Str::random(64)
  ];
});
