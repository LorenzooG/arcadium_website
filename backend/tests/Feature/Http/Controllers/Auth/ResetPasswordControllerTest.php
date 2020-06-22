<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
  public function testShouldResetPasswordWhenCredentialsAreValid()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var PasswordBroker $broker */
    $broker = $this->app->make(PasswordBroker::class);

    $token = $broker->createToken($user);

    $newPassword = $this->faker->password(8, 16);

    $url = route('user.reset.password') . "?token={$token}&email={$user->email}";

    $response = $this->postJson($url, [
      'password' => $newPassword,
    ]);

    $user = User::findOrFail($user->id);

    $this->assertTrue(Hash::check($newPassword, $user->password));

    $response->assertOk()
      ->assertJson([
        'message' => PasswordBroker::PASSWORD_RESET
      ], true);
  }

  public function testShouldNotResetPasswordWhenSendInvalidCredentialsWithInvalidToken()
  {
    Notification::fake();

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var PasswordBroker $broker */
    $broker = $this->app->make(PasswordBroker::class);

    $broker->createToken($user);

    $token = Str::random(120);

    $newPassword = $this->faker->password(8, 16);

    $url = route('user.reset.password') . "?token={$token}&email={$user->email}";

    $response = $this->postJson($url, [
      'password' => $newPassword,
      'email' => $user->email
    ]);

    $user = User::findOrFail($user->id);

    $this->assertFalse(Hash::check($newPassword, $user->password));

    $response->assertOk()
      ->assertJson([
        'message' => PasswordBroker::INVALID_TOKEN
      ], true);
  }
}
