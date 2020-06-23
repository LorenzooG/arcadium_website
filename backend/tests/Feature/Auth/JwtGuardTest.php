<?php


namespace Tests\Feature\Auth;

use App\Auth\JwtGuard;
use App\Repositories\Tokens\JwtRepository;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
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

  public function testShouldValidateSessionWhenTheBearerTokenIsAvailable()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    $request = Request::capture();
    $request->headers->set('Authorization', 'Bearer ' . $repository->create($user));

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
      'request' => $request
    ]);

    /** @var User $dummyUser */
    $dummyUser = $jwt->user();

    $dummyUser->refresh();

    $this->assertEquals($user->id, $dummyUser->id);

    $this->assertTrue($jwt->check());
    $this->assertFalse($jwt->guest());
  }

  public function testShouldLoginUsingId()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
    ]);

    /** @var User $dummy */
    $dummy = $jwt->loginUsingId($user->id);

    $this->assertEquals($dummy->id, $user->id);

    $dummy = $jwt->user();

    $this->assertEquals($dummy->id, $user->id);
  }

  public function testShouldReturnUserIdWhenUserIsNotNull()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    $request = Request::capture();
    $request->headers->set('Authorization', 'Bearer ' . $repository->create($user));

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
      'request' => $request
    ]);

    $id = $jwt->id();

    $this->assertEquals($user->id, $id);

    $this->assertTrue($jwt->check());
    $this->assertFalse($jwt->guest());
  }

  public function testShouldNotReturnUserIdWhenUserNotNull()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
    ]);

    $id = $jwt->id();

    $this->assertNotEquals($user->id, $id);

    $this->assertFalse($jwt->check());
    $this->assertTrue($jwt->guest());
  }

  public function testShouldOnceThrowsException()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
    ]);

    $this->expectException(Exception::class);

    $jwt->once();
  }

  public function testShouldOnceUsingIdThrowsException()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
    ]);

    $this->expectException(Exception::class);

    $jwt->onceUsingId($this->faker->numberBetween());
  }

  public function testShouldViaRememberThrowsException()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
    ]);

    $this->expectException(Exception::class);

    $jwt->viaRemember();
  }

  public function testShouldLogoutThrowsException()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    /** @var User $user */
    $user = factory(User::class)->create();

    /** @var StatefulGuard $jwt */
    $jwt = app(JwtGuard::class, [
      'secret' => config('auth.jwt.secret'),
      'algos' => config('auth.jwt.algos'),
      'jwtRepository' => $repository,
    ]);

    $this->expectException(Exception::class);

    $jwt->logout();
  }
}
