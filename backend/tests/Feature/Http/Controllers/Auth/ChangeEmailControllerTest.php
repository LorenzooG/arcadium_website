<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\EmailUpdate;
use App\Http\Controllers\Auth\ChangeEmailController;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\Tokens\EmailResetTokenRepository;
use App\User;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

final class ChangeEmailControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldUpdateEmail()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    /** @var EmailResetTokenRepository $repository */
    $repository = $this->app[EmailResetTokenRepository::class];

    $token = $repository->create($user);

    $email = $this->faker->unique()->safeEmail;

    $response = $this->actingAs($user)->putJson(route('user.update.email') . "?token=$token", [
      'new_email' => $email
    ]);

    $users = User::query()
      ->where('id', $user->id)
      ->where('name', $user->name)
      ->where('user_name', $user->user_name)
      ->where('email', $email)
      ->where('password', $user->password)
      ->get();

    Notification::assertSentTo($user, VerifyEmailNotification::class);

    $this->assertFalse($repository->exists($user, $token));

    $this->assertCount(1, $users);

    $response->assertNoContent();
  }

  public function testAssertUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ChangeEmailController::class,
      '__invoke',
      UserUpdateEmailRequest::class
    );
  }

  public function testAssertUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ChangeEmailController::class,
      '__invoke',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ChangeEmailController::class,
      '__invoke',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ChangeEmailController::class,
      '__invoke',
      'can:updateSelf,App\User'
    );
  }

}
