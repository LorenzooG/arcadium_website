<?php


namespace Tests\Feature\Auth;

use App\Auth\JwtGuard;
use App\Repositories\Tokens\JwtRepository;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\StatefulGuard;
use Tests\TestCase;

final class JwtGuardTest extends TestCase
{
  public function testShouldCreateTokenWhenCredentialsAreCorrect()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository
    ]);

    $password = $this->faker->password(8, 16);

    /** @var User $user */
    $user = factory(User::class)->create([
      'password' => $password
    ]);

    $jwtToken = $jwt->attempt([
      'email' => $user->email,
      'password' => $password
    ]);

    $payload = JWT::decode($jwtToken, config('auth.jwt.secret'), [config('auth.jwt.algos')]);

    $this->assertTrue(is_string($jwtToken));
    $this->assertTrue($repository->exists($user, $payload->token));
  }

  public function testShouldValidateTokenAndIdAndSetUserWhenLogged()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository
    ]);

    /** @var User $user */
    $user = factory(User::class)->create();

    $payload = JWT::decode($repository->create($user), config('auth.jwt.secret'), [config('auth.jwt.algos')]);

    $payload = [
      'id' => $user->id,
      'token' => $payload->token
    ];

    $this->assertTrue($jwt->validate($payload));

    $this->assertTrue($jwt->check());
    $this->assertFalse($jwt->guest());
  }
}
