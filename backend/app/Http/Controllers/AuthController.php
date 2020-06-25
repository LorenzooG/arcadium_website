<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;

final class AuthController extends Controller
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
  public final function login(LoginRequest $request)
  {
    return [
      'token' => $this->guard->attempt($request->only([
        'email',
        'password'
      ]))
    ];
  }

}
