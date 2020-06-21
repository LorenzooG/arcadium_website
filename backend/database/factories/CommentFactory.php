<?php

/** @var Factory $factory */

use App\Comment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Comment::class, function (Faker $faker) {
  return [
    'content' => $faker->text(140)
  ];
});
