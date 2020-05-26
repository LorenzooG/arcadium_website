<?php

/** @var Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;

$factory->define(Product::class, function (Faker $faker) {
  return [
    "name" => $faker->title,
    "image" => UploadedFile::fake()->image("png"),
    "price" => $faker->numberBetween(0, 100),
    "description" => $faker->text
  ];
});

$factory->afterCreating(Product::class, function (Product $product, Faker $_) {
  $product->commands()->create([
    "command" => "Simple command"
  ]);
});
