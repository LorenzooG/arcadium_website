<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class VerifyEmailController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class VerifyEmailController extends Controller
{
  private UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /**
   * Handle mark verified
   *
   * @param Request $request
   * @return Response
   */
  public function __invoke(Request $request)
  {
    /** @var User $user */
    $user = $this->userRepository->findUserByEmail($request);
    $user->markEmailAsVerified();

    return response()->noContent();
  }

}
