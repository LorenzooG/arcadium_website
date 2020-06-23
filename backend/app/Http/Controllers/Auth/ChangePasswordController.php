<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdatePasswordRequest;
use Illuminate\Http\Response;

/**
 * Class ChangePasswordController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class ChangePasswordController extends Controller
{
  /**
   * Change current user's password
   *
   * @param UserUpdatePasswordRequest $request
   * @return Response
   */
  public function __invoke(UserUpdatePasswordRequest $request)
  {
    $request->user()
      ->fill([
        'password' => $request->get('new_password')
      ])
      ->save();

    return response()->noContent();
  }
}
