<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

class UsersController extends Controller
{

  private UserRepository $userRepository;
  private Repository $cacheRepository;

  /**
   * UsersController constructor
   *
   * @param UserRepository $userRepository
   * @param Repository $cacheRepository
   */
  public function __construct(UserRepository $userRepository, Repository $cacheRepository)
  {
    $this->userRepository = $userRepository;
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find and show all users in a page
   *
   * @return ResourceCollection
   */
  public function index()
  {
    $page = Paginator::resolveCurrentPage();

    return UserResource::collection($this->userRepository->findAllUsersInPage($page));
  }

  /**
   * Find and show an user by it's id
   *
   * @param User $user
   * @return UserResource
   */
  public function show(User $user)
  {
    return new UserResource($user);
  }

  /**
   * Store user in database
   *
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

    return new UserResource($this->userRepository->store($data));
  }

  /**
   * Find and update user
   *
   * @param User $user
   * @param UserUpdateRequest $request
   * @return Response
   */
  public function update(User $user, UserUpdateRequest $request)
  {
    $this->cacheRepository->forget($this->userRepository->getCacheKey("show.{$user->id}"));

    $user->update($request->only([
      'email',
      'password',
      'name',
      'user_name',
    ]));

    return response()->noContent();
  }

  /**
   * Find and delete user
   *
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
