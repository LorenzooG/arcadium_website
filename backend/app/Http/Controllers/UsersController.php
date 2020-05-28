<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

class UsersController extends Controller
{

  protected UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /**
   * @return AnonymousResourceCollection
   */
  public function index()
  {
    $page = Paginator::resolveCurrentPage();

    return UserResource::collection($this->userRepository->all($page));
  }

  /**
   * @param int $user
   * @return UserResource
   */
  public function show(int $user)
  {
    return $this->userRepository->show($user);
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
