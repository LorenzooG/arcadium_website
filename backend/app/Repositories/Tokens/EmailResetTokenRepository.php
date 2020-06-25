<?php


namespace App\Repositories\Tokens;

use App\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

/**
 * Class EmailResetTokenRepository
 *
 * @package App\Repositories\Tokens
 */
final class EmailResetTokenRepository implements TokenRepositoryInterface
{
  private Builder $table;
  private int $expires;
  private int $throttle;

  /**
   * EmailResetTokenRepository constructor
   *
   * @param ConnectionInterface $connection
   * @param int $expires
   * @param int $throttle
   * @param string $tableName
   */
  public function __construct(ConnectionInterface $connection, $expires, $throttle, $tableName = 'email_reset_tokens')
  {
    $this->table = $connection->table($tableName);
    $this->expires = $expires;
    $this->throttle = $throttle;
  }


  /**
   * Creates a token associated with $user
   *
   * @param CanResetPasswordContract|User $user
   * @return string
   */
  public function create(CanResetPasswordContract $user)
  {
    $userId = $user->id;

    $token = hash('sha256', json_encode([
      'id' => $userId,
      'time' => now()->unix()
    ]));

    $this->table->insert([
      'user_id' => $userId,
      'token' => $token,
      'created_at' => now()
    ]);

    return $token;
  }

  /**
   * Checks if $token associated with $user exists
   *
   * @param CanResetPasswordContract|User $user
   * @param string $token
   * @return bool
   */
  public function exists(CanResetPasswordContract $user, $token)
  {
    return $this->table
      ->where('user_id', $user->id)
      ->where('token', $token)
      ->where('created_at', '>', now()->subHours($this->expires))
      ->exists();
  }

  /**
   * Checks if $user has a recently created token
   *
   * @param CanResetPasswordContract|User $user
   * @return bool|void
   */
  public function recentlyCreatedToken(CanResetPasswordContract $user)
  {
    $record = $this->table->where('user_id', $user->id)->first();
    $createdAt = $record->getAttribute('created_at');

    return $createdAt
      ->addHours($this->throttle)
      ->isFuture();
  }

  /**
   * Delete tokens associated with $user
   *
   * @param CanResetPasswordContract|User $user
   * @return bool|void
   */
  public function delete(CanResetPasswordContract $user)
  {
    return $this->table
      ->where('user_id', $user->id)
      ->delete();
  }

  /**
   * Deletes expired tokens
   *
   * @return bool
   */
  public function deleteExpired()
  {
    // TODO: fix this method's test

    return $this->table
      ->where('created_at', '<', now()->subDays($this->expires))
      ->delete();
  }
}
