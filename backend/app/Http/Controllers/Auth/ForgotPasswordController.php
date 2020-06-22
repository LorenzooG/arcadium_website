<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{

  /**
   * Handle send password reset notification
   *
   * @param ForgotPasswordRequest $request
   * @return Response
   */
  public function __invoke(ForgotPasswordRequest $request)
  {
    $this->broker()->sendResetLink($request->only([
      'email'
    ]));

    return response()->noContent();
  }

  /**
   * Get the broker to be used during password reset.
   *
   * @return PasswordBroker
   * @noinspection PhpUndefinedMethodInspection
   */
  public final function broker()
  {
    return Password::broker('');
  }

}
