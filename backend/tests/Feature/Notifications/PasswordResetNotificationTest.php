<?php


namespace Tests\Feature\Notifications;

use App\Notifications\PasswordResetNotification;
use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;

final class PasswordResetNotificationTest extends TestCase
{
  public function testShouldRenderPasswordResetNotificationCorrectly()
  {
    $token = Str::random(72);
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new PasswordResetNotification($token);

    $rendered = $notification->toMail($user)->render();

    $this->assertStringContainsString($user->name, $rendered);
    $this->assertStringContainsString($user->email, $rendered);
    $this->assertStringContainsString($token, $rendered);
  }

  public function testShouldViaInPasswordResetNotificationReturnArrayWithEmail()
  {
    $token = Str::random(72);
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new PasswordResetNotification($token);

    $this->assertEquals(['mail'], $notification->via($user));
  }

  public function testShouldToArrayInPasswordResetNotificationReturnArrayWithTokenAndUser()
  {
    $token = Str::random(72);
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new PasswordResetNotification($token);

    $this->assertEquals([
      'token' => $token,
      'user' => $user
    ], $notification->toArray($user));
  }
}
