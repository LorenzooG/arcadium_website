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

  public final function __construct(UserRepository $userRepository, JwtRepository $jwtRepository, Hasher $hasher)
  {
    $this->jwtRepository = $jwtRepository;
    $this->userRepository = $userRepository;
    $this->hasher = $hasher;
  }

  /**
   * @inheritDoc
   */
  public final function check()
  {
    return $this->user != null;
  }

  /**
   * @inheritDoc
   */
  public final function guest()
  {
    return $this->user == null;
  }

  /**
   * @inheritDoc
   */
  public final function user()
  {
    if ($this->check() || $this->validate()) return $this->user;

    return new User();
  }

  /**
   * @inheritDoc
   */
  public final function id(): int
  {
    return $this->user->id;
  }

  /**
   * @inheritDoc
   */
  public final function validate(array $credentials = [])
  {
    $id = $credentials['id'];
    $token = $credentials['token'];

    $user = $this->userRepository->findUserById($id);
    $result = $this->jwtRepository->exists($user, $token);

    $this->setUser($user);

    return $result;
  }

  /**
   * @inheritDoc
   */
  public final function setUser(Authenticatable $user)
  {
    $this->isLogged = true;

    $this->user = $user;
  }

  /**
   * @inheritDoc
   * @throws Exception
   */
  public final function once(array $credentials = [])
  {
    throw new Exception("Not implemented");
  }

  /**
   * @inheritDoc
   */
  public final function loginUsingId($id, $remember = false)
  {
    return $this->login(User::findOrFail($id), $remember);
  }

  /**
   * @inheritDoc
   * @throws Exception
   */
  public final function onceUsingId($id)
  {
    throw new Exception("Not implemented");
  }

  /**
   * @inheritDoc
   * @throws Exception
   */
  public final function viaRemember()
  {
    throw new Exception("Not implemented");
  }

  /**
   * @inheritDoc
   * @throws Exception
   */
  public final function logout()
  {
    throw new Exception("Not implemented");
  }

  /**
   * @inheritDoc
   */
  public final function login(Authenticatable $user, $remember = false)
  {
    $this->setUser($user);

    return $user;
  }

  /**
   * @inheritDoc
   */
  public final function attempt(array $credentials = [], $remember = false)
  {
    $user = $this->userRepository->findUserByEmail($credentials['email']);

    if (!$this->hasher->check($credentials['password'], $user->password)) return false;

    return $this->jwtRepository->create($user);
  }

}
