<?php


namespace App\Repositories\Tokens;

use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

/**
 * Class JwtRepository
 *
 * @package App\Repositories\Tokens
 */
final class JwtRepository implements TokenRepositoryInterface
{
  private Builder $table;
  private string $secret;
  private string $algos;
  private string $hashAlgos;
  private int $throttle;

  /**
   * JwtRepository constructor
   *
   * @param ConnectionInterface $connection
   * @param string $secret
   * @param string $algos
   * @param string $hashAlgos
   * @param int $throttle
   */
  public function __construct(ConnectionInterface $connection, $secret, $algos, $hashAlgos, $throttle)
  {
    $this->table = $connection->table('jwt_tokens');
    $this->secret = $secret;
    $this->algos = $algos;
    $this->hashAlgos = $hashAlgos;
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

    $databaseToken = hash_hmac($this->hashAlgos, json_encode([
      'id' => $userId,
      'time' => now()->unix()
    ]), $this->secret);

    $payload = [
      'id' => $userId,
      'token' => $databaseToken
    ];

    $token = JWT::encode($payload, $this->secret, $this->algos);

    $this->table->insert([
      'user_id' => $userId,
      'token' => $databaseToken,
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
    $row = $this->table->where('user_id', $user->id)->first();

    if ($row->doesntExist()) return false;

    $createdAt = $row->getAttribute($row->getCreatedAtColumn());

    return Carbon::parse($createdAt)
      ->addDays($this->throttle)
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
   * @return bool|void
   */
  public function deleteExpired()
  {
    // TODO: Implement deleteExpired() method.
  }
}
