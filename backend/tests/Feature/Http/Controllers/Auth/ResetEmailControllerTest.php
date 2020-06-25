<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ChangeEmailController;
use App\Notifications\EmailResetNotification;
use App\Repositories\Tokens\EmailResetTokenRepository;
use App\User;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

final class ResetEmailControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldSendEmailChangeNotification()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    /** @var EmailResetTokenRepository $repository */
    $repository = $this->app[EmailResetTokenRepository::class];

    // Delete old tokens
    $repository->delete($user);
    $repository->deleteExpired();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $response = $this->actingAs($user)->postJson(route('user.request.update.email'), [
      'email' => $user->email,
    ]);

    Notification::assertSentTo($user, EmailResetNotification::class);

    $response->assertNoContent();
  }

  public function testAssertUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ChangeEmailController::class,
      '__invoke',
      'can:updateSelf,App\User'
    );
  }
}
