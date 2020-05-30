<?php

namespace App\Observers;

use App\Repositories\UserRepository;
use App\User;

class UserObserver
{

  private UserRepository $userRepository;

  /**
   * UserObserver constructor
   *
   * @param UserRepository $userRepository
   */
  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }


  /**
   * Handle the user "created" event.
   *
   * @param User $user
   * @return void
   */
  public function created(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "updated" event.
   *
   * @param User $user
   * @return void
   */
  public function updated(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "deleted" event.
   *
   * @param User $user
   * @return void
   */
  public function deleted(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "restored" event.
   *
   * @param User $user
   * @return void
   */
  public function restored(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "force deleted" event.
   *
   * @param User $user
   * @return void
   */
  public function forceDeleted(User $user)
  {
    $this->userRepository->flushCache();
  }
}
