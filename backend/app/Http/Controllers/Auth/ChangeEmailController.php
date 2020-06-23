<?php


namespace App\Http\Controllers\Auth;

use App\EmailUpdate;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateEmailRequest;
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
   * @param EmailUpdate $emailUpdate
   * @param UserUpdateEmailRequest $request
   * @return Response
   */
  public function __invoke(EmailUpdate $emailUpdate, UserUpdateEmailRequest $request)
  {
    $emailUpdate->update([
      'already_used' => true
    ]);

    $request->user()
      ->fill([
        'email' => $request->get('new_email')
      ])
      ->save();

    return response()->noContent();
  }
}
