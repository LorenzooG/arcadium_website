<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateEmailRequest;
use App\User;
use Illuminate\Http\Response;

/**
 * Class ChangeEmailController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class ChangeEmailController extends Controller
{
  /**
   * Changes current user's email
   *
   * @param UserUpdateEmailRequest $request
   * @return Response
   */
  public function __invoke(UserUpdateEmailRequest $request)
  {
    /** @var User $user */
    $user = $request->user();

    $user->update([
      'email' => $request->get('new_email'),
      'email_verified_at' => null
    ]);

    // Resent email verification notification when change email.
    $user->sendEmailVerificationNotification();

    return response()->noContent();
  }
}
