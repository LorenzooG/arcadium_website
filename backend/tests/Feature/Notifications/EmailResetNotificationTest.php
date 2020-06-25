<?php


namespace Tests\Feature\Notifications;

use App\Notifications\EmailResetNotification;
use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;

final class EmailResetNotificationTest extends TestCase
{
  public function testShouldRenderEmailResetNotificationCorrectly()
  {
    $token = Str::random(72);
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new EmailResetNotification($token);

    $rendered = $notification->toMail($user)->render();

    $this->assertStringContainsString($user->name, $rendered);
    $this->assertStringContainsString($token, $rendered);
  }

  public function testShouldViaInEmailResetNotificationReturnArrayWithEmail()
  {
    $token = Str::random(72);
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new EmailResetNotification($token);

    $this->assertEquals(['mail'], $notification->via($user));
  }

  public function testShouldToArrayInEmailResetNotificationReturnArrayWithTokenAndUser()
  {
    $token = Str::random(72);
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new EmailResetNotification($token);

    $this->assertEquals([
      'token' => $token,
      'user' => $user
    ], $notification->toArray($user));
  }
}
