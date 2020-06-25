<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class LoginController extends Controller
{
  private StatefulGuard $guard;

  /**
   * AuthController constructor
   *
   * @param AuthFactory $auth
   */
  public function __construct(AuthFactory $auth)
  {
    $this->guard = $auth->guard();
  }

  /**
   * Handle user login
   *
   * @param LoginRequest $request
   * @return array
   */
  public function __invoke(LoginRequest $request)
  {
    return [
      'token' => $this->guard->attempt($request->only([
        'email',
        'password'
      ]))
    ];
  }

}
