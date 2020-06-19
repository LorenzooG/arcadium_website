<?php

/** @var Factory $factory */

use App\Payment;
use App\Payment\Handlers\MercadoPagoPaymentHandler;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Payment::class, function (Faker $faker) {
  return [
    'user_name' => $faker->userName,
    'total_price' => $faker->numberBetween(10, 300),
    'payment_method' => MercadoPagoPaymentHandler::KEY,
    'origin_address' => $faker->ipv4
  ];
});
