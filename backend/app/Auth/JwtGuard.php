<?php

namespace App\Auth;

use App\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

final class JwtGuard implements StatefulGuard
{
  private ?Authenticatable $user;
  private bool $isLogged;

  public final function __construct()
  {
    $this->isLogged = false;
    $this->user = null;
  }

  /**
   * @inheritDoc
   */
  public final function check()
  {
    return $this->isLogged;
  }

  /**
   * @inheritDoc
   */
  public final function guest()
  {
    return !$this->isLogged;
  }

  /**
   * @inheritDoc
   */
  public final function user()
  {
    if ($this->isLogged) {
      return $this->user;
    }

    $token = Request::bearerToken();
    $secret = config("auth.jwt_secret");

    if (isset($token) && $token !== null && strlen($token) > 0) {
      try {
        $userId = (int)JWT::decode($token, $secret, ["HS256"]);
        if ($userId !== null) {
          $this->loginUsingId($userId);
        }
      } catch (Throwable $exception) {
        throw new BadRequestHttpException();
      }
    }

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
    $query = User::query()
      ->where("email", "=", $credentials["email"]);

    if ($query->exists()) {
      $user = $query->first();

      if (password_verify($credentials["password"], $user->password)) {
        return true;
      }
    }

    return false;
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

    if ($this->validate($credentials)) {
      $query = User::query()
        ->where("email", "=", $credentials["email"]);
      $user = $query->first();

      return JWT::encode($user->id, config("auth.jwt_secret"));
    }

    throw new UnauthorizedHttpException("");
  }

}
