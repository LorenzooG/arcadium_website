<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ResendVerifyEmailNotificationController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class ResendVerifyEmailNotificationController extends Controller
{

  /**
   * Resend verify email notification
   *
   * @param Request $request
   * @return Response
   */
  public function __invoke(Request $request)
  {
    /** @var User $user */
    $user = $request->user();
    $user->sendEmailVerificationNotification();

    return response()->noContent();
  }

}
