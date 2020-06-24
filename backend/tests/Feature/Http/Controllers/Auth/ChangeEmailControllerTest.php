<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\EmailUpdate;
use App\Http\Controllers\Auth\ChangeEmailController;
use App\Http\Requests\UserUpdateEmailRequest;
use App\User;
use Illuminate\Support\Str;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

final class ChangeEmailControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldUpdateEmail()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $token = Str::random(64);

    /** @var EmailUpdate $request */
    $request = factory(EmailUpdate::class)->create([
      'user_id' => $user->id,
      'token' => $token,
    ]);

    $email = $this->faker->unique()->safeEmail;

    $response = $this->actingAs($user)->putJson(route('user.update.email', [
      'emailUpdate' => $token
    ]), [
      'new_email' => $email
    ]);

    $users = User::query()
      ->where('id', $user->id)
      ->where('name', $user->name)
      ->where('user_name', $user->user_name)
      ->where('email', $email)
      ->where('password', $user->password)
      ->get();

    $request = EmailUpdate::findOrFail($request->id);

    $this->assertFalse($request->isValid());
    $this->assertEquals(1, $request->already_used);

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
