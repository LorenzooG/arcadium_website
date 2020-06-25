<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class VerifyEmailControllerTest extends TestCase
{

  public function testShouldVerifyEmail()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->create();

    $signedUrl = url()->temporarySignedRoute('user.verify.email',
      now()->addHours(5), [
        'email' => $user->email
      ]);

    $response = $this->postJson($signedUrl);

    $this->assertTrue($user->hasVerifiedEmail());

    $response->assertNoContent();
  }

}
