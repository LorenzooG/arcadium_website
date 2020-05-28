<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

class UsersController extends Controller
{

  /**
   * User repository
   *
   * @var UserRepository
   */
  protected UserRepository $userRepository;

  /**
   * UsersController constructor
   *
   * @param UserRepository $userRepository
   */
  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
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
   * @param int $user
   * @return UserResource
   */
  public function show(int $user)
  {
    return new UserResource($this->userRepository->findUserById($user));
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
   * Find and update user by it's id
   *
   * @param int $user
   * @param UserUpdateRequest $request
   * @return Response
   */
  public function update(int $user, UserUpdateRequest $request)
  {
    $this->userRepository->updateUserById($user, $request->only([
      'email',
      'password',
      'name',
      'user_name',
    ]));

    return response()->noContent();
  }

  /**
   * Find and delete user by it's id
   *
   * @param int $user
   * @return Response
   * @throws Exception
   */
  public function delete(int $user)
  {
    $this->userRepository->deleteUserById($user);

    return response()->noContent();
  }

}
