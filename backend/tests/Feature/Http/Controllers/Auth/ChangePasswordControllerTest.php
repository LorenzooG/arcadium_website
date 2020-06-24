<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

final class ChangePasswordControllerTest extends TestCase
{
  use AdditionalAssertions;

  public function testShouldUpdatePassword()
  {
    $password = $this->faker->password(8, 16);
    $newPassword = $this->faker->password(8, 16);

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create([
      'password' => $password,
    ]);

    $response = $this->actingAs($user)->putJson(route('user.update.password'), [
      'new_password' => $newPassword,
      'password' => $password
    ]);

    $this->assertTrue(Hash::check($newPassword, $user->password));

    $response->assertNoContent();
  }

  public function testAssertUsesFormRequest()
  {
    $this->assertActionUsesFormRequest(
      ChangePasswordController::class,
      '__invoke',
      UserUpdatePasswordRequest::class
    );
  }

  public function testAssertUsesMiddleware()
  {
    $this->assertActionUsesMiddleware(
      ChangePasswordController::class,
      '__invoke',
      'auth:api'
    );

    $this->assertActionUsesMiddleware(
      ChangePasswordController::class,
      '__invoke',
      'xss'
    );

    $this->assertActionUsesMiddleware(
      ChangePasswordController::class,
      '__invoke',
      'can:updateSelf,App\User'
    );
  }
}
