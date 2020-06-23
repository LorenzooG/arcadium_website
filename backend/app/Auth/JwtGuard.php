<?php

namespace App\Auth;

use App\Repositories\Tokens\JwtRepository;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Hashing\Hasher;

final class JwtGuard implements StatefulGuard
{
  /**
   * Target's user
   *
   * @var Authenticatable|User|null $user
   */
  protected ?Authenticatable $user = null;

  private TokenRepositoryInterface $jwtRepository;
  private UserRepository $userRepository;
  private Hasher $hasher;

  /**
   * JwtGuard constructor
   *
   * @param UserRepository $userRepository
   * @param JwtRepository $jwtRepository
   * @param Hasher $hasher
   */
  public final function __construct(UserRepository $userRepository, JwtRepository $jwtRepository, Hasher $hasher)
  {
    $this->jwtRepository = $jwtRepository;
    $this->userRepository = $userRepository;
    $this->hasher = $hasher;
  }

  /**
   * Returns the session user or a default instance
   *
   * @return User|Authenticatable|null
   */
  public final function user()
  {
    if ($this->check() || $this->validate()) return $this->user;

    return new User();
  }

  /**
   * Checks if user is logged and if is logged set the current user
   *
   * @param array $credentials
   * @return bool
   */
  public final function validate(array $credentials = [])
  {
    $id = $credentials['id'];
    $token = $credentials['token'];

    $user = $this->userRepository->findUserById($id);
    $result = $this->jwtRepository->exists($user, $token);

    if ($result) $this->setUser($user);

    return $result;
  }

  /**
   * Tries to login with credentials
   *
   * @param array $credentials
   * @param bool $remember
   * @return bool|string
   */
  public final function attempt(array $credentials = [], $remember = false)
  {
    $user = $this->userRepository->findUserByEmail($credentials['email']);

    if (!$this->hasher->check($credentials['password'], $user->password)) return false;

    return $this->jwtRepository->create($user);
  }

  /**
   * Sets the current user
   *
   * @param Authenticatable $user
   */
  public final function setUser(Authenticatable $user)
  {
    $this->user = $user;
  }

  /**
   * Returns the session user id
   *
   * @return int
   */
  public final function id(): int
  {
    return $this->user->id;
  }

  /**
   * Returns if user is logged
   *
   * @return bool
   */
  public final function check()
  {
    return $this->user != null;
  }

  /**
   * Returns if user is guest
   *
   * @return bool
   */
  public final function guest()
  {
    return $this->user == null;
  }

  /**
   * Logins user by its id
   *
   * @param mixed $id
   * @param bool $remember
   * @return Authenticatable|void
   */
  public final function loginUsingId($id, $remember = false)
  {
    return $this->login(User::findOrFail($id), $remember);
  }

  /**
   * Logins user with the instance
   *
   * @param Authenticatable $user
   * @param bool $remember
   * @return Authenticatable|void
   */
  public final function login(Authenticatable $user, $remember = false)
  {
    $this->setUser($user);

    return $user;
  }

  /**
   * Not implemented
   *
   * @param array $credentials
   * @return bool|void
   * @throws Exception
   */
  public final function once(array $credentials = [])
  {
    throw new Exception("Not implemented");
  }

  /**
   * Not implement
   *
   * @return bool|void
   * @throws Exception
   */
  public final function viaRemember()
  {
    throw new Exception("Not implemented");
  }

  /**
   * Not implemented
   *
   * @param mixed $id
   * @return bool|Authenticatable|void
   * @throws Exception
   */
  public final function onceUsingId($id)
  {
    throw new Exception("Not implemented");
  }

  /**
   * Not implemented
   *
   * @throws Exception
   */
  public final function logout()
  {
    throw new Exception("Not implemented");
  }

}
