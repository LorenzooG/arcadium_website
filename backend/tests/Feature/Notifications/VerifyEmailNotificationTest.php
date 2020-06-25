<?php


namespace Tests\Feature\Notifications;

use App\Notifications\VerifyEmailNotification;
use App\User;
use Tests\TestCase;

final class VerifyEmailNotificationTest extends TestCase
{
  public function testShouldRenderVerifyEmailNotificationCorrectly()
  {
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new VerifyEmailNotification();

    $rendered = $notification->toMail($user)->render();

    $this->assertStringContainsString($user->name, $rendered);
  }

  public function testShouldViaInVerifyEmailNotificationReturnArrayWithEmail()
  {
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new VerifyEmailNotification();

    $this->assertEquals(['mail'], $notification->via($user));
  }

  public function testShouldToArrayInVerifyEmailNotificationReturnArrayWithProductsAndUser()
  {
    /** @var User $user */
    $user = factory(User::class)->make();

    $notification = new VerifyEmailNotification();

    $this->assertEquals([
      'user' => $user,
      'url' => url()->temporarySignedRoute('user.verify.email',
        now()->addHours(config('auth.verification.expires')), [
          'email' => $user->getEmailForVerification()
        ])
    ], $notification->toArray($user));
  }
}
