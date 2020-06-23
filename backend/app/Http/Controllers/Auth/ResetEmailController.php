<?php


namespace App\Http\Controllers\Auth;


use App\EmailUpdate;
use App\Http\Controllers\Controller;
use App\Notifications\EmailResetNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ResetEmailController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class ResetEmailController extends Controller
{
  /**
   * Sends reset email notification
   *
   * @param Request $request
   * @return Response
   */
  public function __invoke(Request $request)
  {
    /** @var User $user */
    $user = $request->user();

    /** @var EmailUpdate $emailUpdate */
    $emailUpdate = $user->emailUpdates()->create([
      'origin_address' => $request->ip(),
      'token' => hash('sha256', json_encode([
        'user_id' => $user->id,
        'time' => microtime(true)
      ]))
    ]);

    $user->notify(new EmailResetNotification($emailUpdate));

    return response()->noContent();
  }
}
