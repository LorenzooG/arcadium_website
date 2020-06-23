<?php


namespace Tests\Feature\Repositories\Tokens;

use App\Repositories\Tokens\JwtRepository;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;
use Tests\TestCase;

final class JwtRepositoryTest extends TestCase
{

  public function testShouldRecentlyCreatedTokenReturnFalse()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    $user = factory(User::class)->make();

    $this->assertFalse($repository->recentlyCreatedToken($user));
  }

  public function testShouldDeleteDeleteUsersTokensAndReturnTrue()
  {
    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class);

    $user = factory(User::class)->create();

    $token = $repository->create($user);
    $token = JWT::decode($token, config('auth.jwt.secret'), [config('auth.jwt.algos')])->token;

    $this->assertEquals(1, $repository->delete($user));
    $this->assertFalse($repository->exists($user, $token));
  }

  public function testShouldDeleteDeleteExpiredTokensAndReturnTrue()
  {
    $tableName = config('auth.jwt.table', 'jwt_tokens');

    /** @var ConnectionInterface $conn */
    $conn = app(ConnectionInterface::class);
    $table = $conn->table($tableName);

    /** @var JwtRepository $repository */
    $repository = app(JwtRepository::class, [
      'tableName' => $tableName
    ]);

    /** @var User $user */
    $user = factory(User::class)->create();
    $token = Str::random(64);
    $createdAt = now()->addDays(config('auth.jwt.expires') + 15);

    $table->insert([
      'user_id' => $user->id,
      'token' => $token,
      'created_at' => $createdAt
    ]);

    $this->assertEquals(0, $repository->deleteExpired());
  }

}
