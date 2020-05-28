<?php

namespace App\Http\Controllers;

use App\EmailUpdate;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Http\Response;

class UserController extends Controller
{
  /**
   * Update current user's name and user name
   *
   * @param UserUpdateRequest $request
   * @return Response
   */
  public function update(UserUpdateRequest $request)
  {
    $request->user()
      ->fill($request->only([
        'name',
        'user_name'
      ]))
      ->save();

    return response()->noContent();
  }

  /**
   * Update current user's password
   *
   * @param UserUpdatePasswordRequest $request
   * @return Response
   */
  public function updatePassword(UserUpdatePasswordRequest $request)
  {
    $request->user()
      ->fill([
        'password' => $request->get('new_password')
      ])
      ->save();

    return response()->noContent();
  }

  /**
   * Update current user's email
   *
   * @param EmailUpdate $email_update
   * @param UserUpdateEmailRequest $request
   * @return Response
   */
  public function updateEmail(EmailUpdate $email_update, UserUpdateEmailRequest $request)
  {
    $email_update->already_used = true;

    $request->user()
      ->fill([
        'email' => $request->get('new_email')
      ])
      ->save();

    return response()->noContent();
  }

  /**
   * Delete current user
   *
   * @param UserDeleteRequest $request
   * @return Response
   * @throws Exception
   */
  public function delete(UserDeleteRequest $request)
  {
    $request->user()->delete();

    return response()->noContent();
  }
}
