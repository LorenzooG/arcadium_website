<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Pagination\Paginator;

final class StaffController extends Controller
{

  private UserRepository $userRepository;

  /**
   * StaffController constructor
   *
   * @param UserRepository $userRepository
   */
  public final function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /**
   * Find and show paginated staff members
   */
  public final function index()
  {
    $page = Paginator::resolveCurrentPage();

    return $this->userRepository->findAllUsersThatAreStaff($page);
  }

}
