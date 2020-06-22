<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\Notifications\PasswordResetNotification;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
  public function testShouldSendNotificationWhenPostUserForgotPassword()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->create();

    $response = $this->postJson(route('user.forgot.password', [
      'email' => $user->email
    ]));

    Notification::assertSentTo($user, PasswordResetNotification::class);

    $response->assertNoContent();
  }
}
