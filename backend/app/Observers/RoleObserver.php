<?php

namespace App\Observers;

use App\Repositories\RoleRepository;
use App\Role;

class RoleObserver
{

  private RoleRepository $roleRepository;

  /**
   * RoleObserver constructor
   *
   * @param RoleRepository $roleRepository
   */
  public function __construct(RoleRepository $roleRepository)
  {
    $this->roleRepository = $roleRepository;
  }

  /**
   * Handle the role "created" event.
   *
   * @param Role $role
   * @return void
   */
  public function created(Role $role)
  {
    $this->roleRepository->flushCache();
  }

  /**
   * Handle the role "updated" event.
   *
   * @param Role $role
   * @return void
   */
  public function updated(Role $role)
  {
    $this->roleRepository->flushCache();
  }

  /**
   * Handle the role "deleted" event.
   *
   * @param Role $role
   * @return void
   */
  public function deleted(Role $role)
  {
    $this->roleRepository->flushCache();
  }

  /**
   * Handle the role "restored" event.
   *
   * @param Role $role
   * @return void
   */
  public function restored(Role $role)
  {
    $this->roleRepository->flushCache();
  }

  /**
   * Handle the role "force deleted" event.
   *
   * @param Role $role
   * @return void
   */
  public function forceDeleted(Role $role)
  {
    $this->roleRepository->flushCache();
  }
}
