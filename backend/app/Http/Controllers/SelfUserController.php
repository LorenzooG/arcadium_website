<?php

namespace App\Http\Controllers;

use App\EmailUpdate;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
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

class SelfUserController extends Controller
{

	private PostRepository $postRepository;
	private RoleRepository $roleRepository;
	
	/**
	 * SelfUserController constructor
	 *
	 * @param PostRepository $postRepository
	 * @param RoleRepository $roleRepository
	 */
	public function __construct(PostRepository $postRepository, RoleRepository $roleRepository)
	{
		$this->postRepository = $postRepository;
		$this->roleRepository = $roleRepository;
	}

	/**
	 * Show the current user's posts
	 *
	 * @return AnonymousResourceCollection
	 */
	public function roles(Request $request)
	{
		$page = Paginator::resolveCurrentPage();

		return RoleResource::collection($this->roleRepository->findPaginatedRolesForUser($request->user(), $page));
	}

	/**
	 * Show the current user's roles
	 *
	 * @return AnonymousResourceCollection
	 */
	public function posts(Request $request)
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
