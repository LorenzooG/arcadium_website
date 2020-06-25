<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\Notifications\VerifyEmailNotification;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class ResendVerifyEmailNotificationControllerTest extends TestCase
{
  public function testShouldSendVerifyEmailNotification()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('user.resend.verify.email.notification'));

    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 2);

    $response->assertNoContent();
  }
}
