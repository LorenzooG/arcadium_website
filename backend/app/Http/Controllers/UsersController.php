<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

final class UsersController extends Controller
{

  private UserRepository $userRepository;

  /**
   * UsersController constructor
   *
   * @param UserRepository $userRepository
   */
  public final function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /**
   * Find and show all users in a page
   *
   * @return ResourceCollection
   */
  public final function index()
  {
    $page = Paginator::resolveCurrentPage();

    return UserResource::collection($this->userRepository->findPaginatedUsers($page));
  }

  /**
   * Find and show an user by it's id
   *
   * @param User $user
   * @return UserResource
   */
  public final function show(User $user)
  {
    return new UserResource($user);
  }

  /**
   * Store user in database
   *
   * @param UserStoreRequest $request
   * @return UserResource
   */
  public final function store(UserStoreRequest $request)
  {
    $data = $request->only([
      'email',
      'password',
      'name',
      'user_name'
    ]);

    $data['avatar_url'] = '';

    $user = $this->userRepository->createUser($data);

    return new UserResource($user);
  }

  /**
   * Find and update user
   *
   * @param User $user
   * @param UserUpdateRequest $request
   * @return Response
   */
  public final function update(User $user, UserUpdateRequest $request)
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
   * Find and delete user
   *
   * @param User $user
   * @return Response
   * @throws Exception
   */
  public final function delete(User $user)
  {
    $user->delete();

    return response()->noContent();
  }

  /**
   * Find and restore deleted user
   *
   * @param User $user
   * @return Response
   * @throws Exception
   */
  public final function restore(User $user)
  {
    $user->restore();

    return response()->noContent();
  }

  /**
   * Find and show all trashed users
   *
   * @return ResourceCollection
   */
  public final function trashed()
  {
    $page = Paginator::resolveCurrentPage();

    return UserResource::collection($this->userRepository->findPaginatedTrashedUsers($page));
  }

}
