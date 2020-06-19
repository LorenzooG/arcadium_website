<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Repositories\RoleRepository;
use App\Role;
use Illuminate\Pagination\Paginator;

final class StaffController extends Controller
{

  private RoleRepository $roleRepository;

  /**
   * StaffController constructor
   *
   * @param RoleRepository $roleRepository
   */
  public final function __construct(RoleRepository $roleRepository)
  {
    $this->roleRepository = $roleRepository;
  }

  /**
   * Find and show paginated staff members
   */
  public final function index()
  {
    $page = Paginator::resolveCurrentPage();

    return $this->roleRepository->findAllRolesThatAreStaff($page)->map(function (Role $role) {
      return [
        'role' => new RoleResource($role),
        'users' => UserResource::collection($role->users),
      ];
    });
  }

}
