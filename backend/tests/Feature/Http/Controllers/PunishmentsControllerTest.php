<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PunishmentsController;
use App\Http\Requests\PunishmentStoreRequest;
use App\Http\Requests\PunishmentUpdateRequest;
use App\Punishment;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class PunishmentsControllerTest extends TestCase
{
  use AdditionalAssertions;

  /**
   * Read all
   */
  public function testShouldShowPunishmentsWhenGetPunishments()
  {
    factory(Punishment::class, 10)->create();

    $response = $this->getJson(route('punishments.index'));

    $response->assertOk()
      ->assertJson([
        'data' => Collection::make(Punishment::query()->orderByDesc('id')->paginate()->items())->map(function (Punishment $punishment) {
          return [
            'id' => $punishment->id,
            'punished_user_name' => $punishment->punished_user_name,
            'reason' => $punishment->reason,
            'proof' => $punishment->proof,
            'punished_duration' => Carbon::createFromTimestampMs($punishment->punished_duration)->toISOString(),
            'punished_until' => Carbon::createFromTimestampMs($punishment->punished_until)->toISOString(),
            'punished_at' => Carbon::createFromTimestampMs($punishment->punished_at)->toISOString(),
            'created_at' => $punishment->created_at->toISOString(),
            'updated_at' => $punishment->updated_at->toISOString(),
          ];
        })->toArray(),
      ]);
  }

  /**
   * Read one
   */
  public function testShouldShowPunishmentWhenGetPunishments()
  {
    /** @var Punishment $punishment */
    $punishment = factory(Punishment::class)->create();

    $response = $this->getJson(route('punishments.show', [
      'punishment' => $punishment->id
    ]));

    $response->assertOk()
      ->assertJson([
        'id' => $punishment->id,
        'punished_user_name' => $punishment->punished_user_name,
        'reason' => $punishment->reason,
        'proof' => $punishment->proof,
        'punished_duration' => Carbon::createFromTimestampMs($punishment->punished_duration)->toISOString(),
        'punished_until' => Carbon::createFromTimestampMs($punishment->punished_until)->toISOString(),
        'punished_at' => Carbon::createFromTimestampMs($punishment->punished_at)->toISOString(),
        'created_at' => $punishment->created_at->toISOString(),
        'updated_at' => $punishment->updated_at->toISOString(),
      ]);
  }

  /**
   * Create
   */
  public function testShouldStorePunishmentsWhenPostPunishments()
  {
    $punishedUserName = $this->faker->text(32);
    $punishedBy = $this->faker->text(32);
    $punishedUntil = $this->faker->unixTime;
    $proof = $this->faker->text(240);
    $reason = $this->faker->text(240);
    $punishedAt = $this->faker->unixTime;

    $punishmentDuration = Carbon::createFromTimestampMs($punishedAt)
      ->diffInMilliseconds(Carbon::createFromTimestampMs($punishedUntil));

    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('punishments.store'), [
      'punished_user_name' => $punishedUserName,
      'punished_at' => $punishedAt,
      'punished_until' => $punishedUntil,
      'punished_by' => $punishedBy,
      'reason' => $reason,
      'proof' => $proof,
    ]);

    $punishments = Punishment::query()
      ->where('punished_user_name', $punishedUserName)
      ->where('punished_until', $punishedUntil)
      ->where('punished_duration', $punishmentDuration)
      ->where('punished_at', $punishedAt)
      ->where('punished_by', $punishedBy)
      ->where('reason', $reason)
      ->where('proof', $proof)
      ->get();

    $punishment = $punishments->first();

    $this->assertCount(1, $punishments);

    $response->assertCreated()
      ->assertJson([
        'id' => $punishment->id,
        'punished_user_name' => $punishment->punished_user_name,
        'reason' => $punishment->reason,
        'proof' => $punishment->proof,
        'punished_duration' => Carbon::createFromTimestampMs($punishment->punished_duration)->toISOString(),
        'punished_until' => Carbon::createFromTimestampMs($punishment->punished_until)->toISOString(),
        'punished_at' => Carbon::createFromTimestampMs($punishment->punished_at)->toISOString(),
        'created_at' => $punishment->created_at->toISOString(),
        'updated_at' => $punishment->updated_at->toISOString(),
      ]);
  }

  public function testAssertStoreUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      PunishmentsController::class,
      'store',
      PunishmentStoreRequest::class
    );
  }

  public function testAssertStoreUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      PunishmentsController::class,
      'store',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      PunishmentsController::class,
      'store',
      'can:create,App\Punishment'
    );
  }

  /**
   * Delete
   */
  public function testShouldDeletePunishmentWhenDeletePunishments()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Punishment $punishment */
    $punishment = factory(Punishment::class)->create();

    $response = $this->actingAs($user)->deleteJson(route('punishments.delete', [
      'punishment' => $punishment->id
    ]));

    $this->assertDeleted($punishment);

    $response->assertNoContent();
  }

  public function testAssertDeleteUsesPermissionMiddleware()
  {
    $this->assertActionUsesMiddleware(
      PunishmentsController::class,
      'delete',
      'can:delete,App\Punishment'
    );
  }

  /**
   * Update
   */
  public function testShouldUpdatePunishmentWhenPutPunishments()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    /** @var Punishment $punishment */
    $punishment = factory(Punishment::class)->create();

    $punishedUserName = $this->faker->text(32);
    $punishedBy = $this->faker->text(32);
    $punishedUntil = $this->faker->unixTime;
    $proof = $this->faker->text(240);
    $reason = $this->faker->text(240);
    $punishedAt = $this->faker->unixTime;

    $punishmentDuration = Carbon::createFromTimestampMs($punishedAt)
      ->diffInMilliseconds(Carbon::createFromTimestampMs($punishedUntil));

    $response = $this->actingAs($user)->putJson(route('comments.update', [
      'punishment' => $punishment->id
    ]), [
      'punished_user_name' => $punishedUserName,
      'punished_at' => $punishedAt,
      'punished_until' => $punishedUntil,
      'punished_by' => $punishedBy,
      'reason' => $reason,
      'proof' => $proof,
    ]);

    $punishments = Punishment::query()
      ->where('punished_user_name', $punishedUserName)
      ->where('punished_until', $punishedUntil)
      ->where('punished_duration', $punishmentDuration)
      ->where('punished_at', $punishedAt)
      ->where('punished_by', $punishedBy)
      ->where('reason', $reason)
      ->where('proof', $proof)
      ->get();

    $this->assertCount(1, $punishments);

    $response->assertNoContent();
  }

  public function testAssertUpdateUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      PunishmentsController::class,
      'update',
      PunishmentUpdateRequest::class
    );
  }

  public function testAssertUpdateUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      PunishmentsController::class,
      'update',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      PunishmentsController::class,
      'update',
      'can:update,App\Punishment'
    );
  }

}
