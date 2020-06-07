<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

class RolesController extends Controller
{
  private RoleRepository $roleRepository;

  /**
   * RolesController constructor
   *
   * @param RoleRepository $roleRepository
   * @param UserRepository $userRepository
   */
  public function __construct(RoleRepository $roleRepository, UserRepository $userRepository)
	{
    $this->roleRepository = $roleRepository;
    $this->userRepository = $userRepository;
  }

  /**
   * Find and show all roles in a page
   *
   * @return ResourceCollection
   */
  public function index()
  {
    $page = Paginator::resolveCurrentPage();

    return RoleResource::collection($this->roleRepository->findPaginatedRoles($page));
	}

	/**
	 * Find and show role
	 *
	 * @return RoleResource
	 */
	public function show(Role $role) {
		return new RoleResource($role);
	}

  /**
   * Find and show all user's roles in a page
   *
   * @param User $user
   * @return ResourceCollection
   */
  public function user(User $user)
  {
		$page = Paginator::resolveCurrentPage();

    return RoleResource::collection($this->roleRepository->findPaginatedRolesForUser($user, $page));
  }

  /**
   * Store role in database
   *
   * @param RoleStoreRequest $request
   * @return RoleResource
   */
  public function store(RoleStoreRequest $request)
  {
    $role = $this->roleRepository->createRole($request->only([
      'title',
      'color',
      'permission_level'
    ]));

    return new RoleResource($role);
  }

  /**
   * Find and delete role
   *
   * @param Role $role
   * @return Response
   * @throws Exception
   */
  public function delete(Role $role)
  {
    $role->delete();

    return response()->noContent();
  }

  /**
   * Find and update role
   *
   * @param Role $role
   * @param RoleUpdateRequest $request
   * @return Response
   */
  public function update(Role $role, RoleUpdateRequest $request)
  {
    $role->update($request->only([
      'title',
      'color',
      'permission_level'
    ]));

    return response()->noContent();
  }

  /**
   * Find and attach role to user
   *
   * @param Role $role
   * @param User $user
   * @return Response
   */
  public function attach(Role $role, User $user)
  {
    $user->roles()->attach($role->id);

    return response()->noContent();
  }

  /**
   * Find and detach role from user
   *
   * @param Role $role
   * @param User $user
   * @return Response
   */
  public function detach(Role $role, User $user)
  {
    $user->roles()->detach($role->id);

    return response()->noContent();
  }
}
