<?php


namespace Tests\Feature\Http\Controllers;

use App\User;
use Tests\TestCase;

final class LoginControllerTest extends TestCase
{
  public function testShouldLogin()
  {
    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();
    $password = $this->faker->password(8, 16);

    $response = $this->postJson(route('login'), [
      'email' => $user->email,
      'password' => $password
    ]);

    $response->assertOk()
      ->assertJsonStructure([
        'token'
      ]);
  }
}
