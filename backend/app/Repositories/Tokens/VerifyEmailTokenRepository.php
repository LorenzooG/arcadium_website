<?php


namespace App\Repositories\Tokens;


use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

/**
 * Class VerifyEmailTokenRepository
 *
 * @package App\Repositories\Tokens
 */
final class VerifyEmailTokenRepository implements TokenRepositoryInterface
{
  private Builder $table;
  private string $algos;
  private int $expires;
  private int $throttle;

  /**
   * VerifyEmailTokenRepository constructor
   *
   * @param ConnectionInterface $connection
   * @param string $algos
   * @param int $expires
   * @param int $throttle
   * @param string $tableName
   */
  public function __construct(ConnectionInterface $connection, string $algos, int $expires, int $throttle, $tableName = 'verify_email_tokens')
  {
    $this->table = $connection->table($tableName);
    $this->algos = $algos;
    $this->expires = $expires;
    $this->throttle = $throttle;
  }

  public function create(CanResetPasswordContract $user)
  {
    // TODO: Implement create() method.
  }

  public function exists(CanResetPasswordContract $user, $token)
  {
    // TODO: Implement exists() method.
  }

  public function recentlyCreatedToken(CanResetPasswordContract $user)
  {
    // TODO: Implement recentlyCreatedToken() method.
  }

  public function delete(CanResetPasswordContract $user)
  {
    // TODO: Implement delete() method.
  }

  public function deleteExpired()
  {
    // TODO: Implement deleteExpired() method.
  }
}
