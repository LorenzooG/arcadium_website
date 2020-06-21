<?php

/** @var Factory $factory */

use App\Punishment;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Punishment::class, function (Faker $faker) {
  $punishedUserName = $this->faker->text(32);
  $punishedBy = $this->faker->text(32);
  $punishedUntil = $this->faker->unixTime;
  $proof = $this->faker->text(240);
  $reason = $this->faker->text(240);
  $punishedAt = $this->faker->unixTime;

  $punishmentDuration = Carbon::createFromTimestampMs($punishedAt)
    ->diffInMilliseconds(Carbon::createFromTimestampMs($punishedUntil));

  return [
    'punished_user_name' => $punishedUserName,
    'punished_at' => $punishedAt,
    'punished_until' => $punishedUntil,
    'punishment_duration' => $punishmentDuration,
    'punished_by' => $punishedBy,
    'reason' => $reason,
    'proof' => $proof,
  ];
});
