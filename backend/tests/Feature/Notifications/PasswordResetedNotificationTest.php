<?php


namespace Tests\Feature\Notifications;

use App\Notifications\PasswordResetedNotification;
use App\User;
use Tests\TestCase;

final class PasswordResetedNotificationTest extends TestCase
{
  public function testShouldRenderPasswordResetedNotificationCorrectly()
  {
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new PasswordResetedNotification();

    $rendered = $notification->toMail($user)->render();

    $this->assertStringContainsString($user->name, $rendered);
  }

  public function testShouldViaInPasswordResetedNotificationReturnArrayWithEmail()
  {
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new PasswordResetedNotification();

    $this->assertEquals(['mail'], $notification->via($user));
  }

  public function testShouldToArrayInPasswordResetedNotificationReturnArrayWithTokenAndUser()
  {
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new PasswordResetedNotification();

    $this->assertEquals([
      'user' => $user
    ], $notification->toArray($user));
  }
}
