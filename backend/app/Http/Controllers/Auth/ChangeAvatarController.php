<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUpdateRequest;
use App\User;
use Illuminate\Http\Response;

/**
 * Class ChangeAvatarController
 *
 * @package App\Http\Controllers\Auth
 * @noinspection PhpUnused
 */
final class ChangeAvatarController extends Controller
{
  /**
   * Handle update current avatar
   *
   * @param ImageUpdateRequest $request
   * @param User|null $user
   * @return Response
   */
  public function __invoke(ImageUpdateRequest $request, ?User $user = null)
  {
    if (is_null($user)) $user = $request->user();

    $request->file('image')->storeAs(User::AVATARS_STORAGE_KEY, $user->id);

    return response()->noContent();
  }
}
