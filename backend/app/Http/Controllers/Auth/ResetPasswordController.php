<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Notifications\PasswordResetedNotification;
use App\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{

  /**
   * Handle send password reset notification
   *
   * @param PasswordResetRequest $request
   * @return array
   */
  public function __invoke(PasswordResetRequest $request)
  {
    $data = $request->only([
      'password'
    ]);

    $data = array_merge($data, [
      'token' => $request->query('token', ''),
      'email' => $request->query('email', '')
    ]);

    $result = $this->broker()->reset($data, function (User $user, $newPassword) {
      $user->update([
        'password' => $newPassword
      ]);

      $user->notify(new PasswordResetedNotification());
    });

    return [
      'message' => $result
    ];
  }

  /**
   * Get the guard to be used during password rest
   *
   * @return StatefulGuard
   */
  public final function guard()
  {
    return Auth::guard('api');
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
