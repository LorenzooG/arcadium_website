<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\EmailResetRequest;
use App\Notifications\EmailResetNotification;
use App\Repositories\Tokens\EmailResetTokenRepository;
use App\User;
use Illuminate\Http\Response;

/**
 * Class ResetEmailController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class ResetEmailController extends Controller
{
  private EmailResetTokenRepository $emailResetTokenRepository;

  /**
   * ResetEmailController constructor
   *
   * @param EmailResetTokenRepository $emailResetTokenRepository
   */
  public function __construct(EmailResetTokenRepository $emailResetTokenRepository)
  {
    $this->emailResetTokenRepository = $emailResetTokenRepository;
  }

  /**
   * Sends reset email notification
   *
   * @param EmailResetRequest $request
   * @return Response
   */
  public function __invoke(EmailResetRequest $request)
  {
    /** @var User $user */
    $user = $request->user();

    $token = $this->emailResetTokenRepository->create($user);

    $user->notify(new EmailResetNotification($token));

    return response()->noContent();
  }
}
