<?php

/** @var Factory $factory */

use App\ProductCommand;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ProductCommand::class, function (Faker $faker) {
  return [
    'command' => $faker->text(72)
  ];
});
