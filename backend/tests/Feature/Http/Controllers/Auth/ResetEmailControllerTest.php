<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\Notifications\EmailResetNotification;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class ResetEmailControllerTest extends TestCase
{
  public function testShouldSendEmailChangeNotification()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('user.request.update.email'), [
      'email' => $user->email,
    ]);

    Notification::assertSentTo($user, EmailResetNotification::class);

    $response->assertNoContent();
  }
}
