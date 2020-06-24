<?php

/** @var Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Product::class, function (Faker $faker) {
  return [
    'title' => $faker->word,
    'description' => $faker->text(6000),
    'price' => $faker->numberBetween(5, 15)
  ];
});

$factory->afterCreating(Product::class, function (Product $product, Faker $faker) {
  $product->commands()->create([
    'command' => $faker->text(72)
  ]);
});
