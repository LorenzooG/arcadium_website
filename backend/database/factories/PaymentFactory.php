<?php

/** @var Factory $factory */

use App\Payment;
use App\Product;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Payment::class, function (Faker $faker) {
  $user = factory(User::class)->create([
    "name" => $faker->name,
    "email" => $faker->unique()->safeEmail,
    "password" => "password"
  ]);

  return [
    "user_name" => "LorenzooG",
    "origin_ip" => "127.0.0.1",
    "user_id" => $user->id,
    "total_price" => $faker->numberBetween(1, 2000),
    "payment_response" => false,
    "payment_raw_response" => "NULL",
    "payment_type" => "MP",
    "delivered" => false,
  ];
});

$factory->afterCreating(Payment::class, function (Payment $payment, Faker $faker) {
  $payment->products()->create([
    "product" => factory(Product::class)->create(),
    "amount" => $faker->numberBetween(1, 15)
  ]);
});
