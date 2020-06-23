<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\RoleResource;
use App\Repositories\PostRepository;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

final class SelfUserController extends Controller
{

  private PostRepository $postRepository;
  private RoleRepository $roleRepository;

  /**
   * SelfUserController constructor
   *
   * @param PostRepository $postRepository
   * @param RoleRepository $roleRepository
   */
  public final function __construct(PostRepository $postRepository, RoleRepository $roleRepository)
  {
    $this->postRepository = $postRepository;
    $this->roleRepository = $roleRepository;
  }

  /**
   * Show the current user's posts
   *
   * @param Request $request
   * @return AnonymousResourceCollection
   */
  public final function roles(Request $request)
  {
    $page = Paginator::resolveCurrentPage();

    return RoleResource::collection($this->roleRepository->findPaginatedRolesForUser($request->user(), $page));
  }

  /**
   * Show the current user's roles
   *
   * @param Request $request
   * @return AnonymousResourceCollection
   */
  public final function posts(Request $request)
  {
    $page = Paginator::resolveCurrentPage();

    return PostResource::collection($this->postRepository->findPaginatedPostsForUser($request->user(), $page));
  }

  /**
   * Update current user's name and user name
   *
   * @param UserUpdateRequest $request
   * @return Response
   */
  public final function update(UserUpdateRequest $request)
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
   * Delete current user
   *
   * @param UserDeleteRequest $request
   * @return Response
   * @throws Exception
   */
  public final function delete(UserDeleteRequest $request)
  {
    $request->user()->delete();

    return response()->noContent();
  }
}
