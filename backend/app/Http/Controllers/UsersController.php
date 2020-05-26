<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UsersController extends Controller
{
  /**
   * @return AnonymousResourceCollection
   */
  public function index()
  {
    return UserResource::collection(User::all());
  }

  /**
   * @param User $user
   * @return UserResource
   */
  public function show(User $user)
  {
    return new UserResource($user);
  }

  /**
   * @param UserStoreRequest $request
   * @return UserResource
   */
  public function store(UserStoreRequest $request)
  {
    $data = $request->only([
      'email',
      'password',
      'name',
      'user_name'
    ]);

    $data['avatar_url'] = '';

    $user = User::create($data);

    return new UserResource($user);
  }

  /**
   * @param User $user
   * @param UserUpdateRequest $request
   * @return Response
   */
  public function update(User $user, UserUpdateRequest $request)
  {
    $user->update($request->only([
      'email',
      'password',
      'name',
      'user_name',
    ]));

    return response()->noContent();
  }

  /**
   * @param User $user
   * @return Response
   * @throws Exception
   */
  public function delete(User $user)
  {
    $user->delete();

    return response()->noContent();
  }

}
