<?php

namespace App\Observers;

use App\Repositories\UserRepository;
use App\User;

final class UserObserver
{

  private UserRepository $userRepository;

  /**
   * UserObserver constructor
   *
   * @param UserRepository $userRepository
   */
  public final function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }


  /**
   * Handle the user "created" event.
   *
   * @param User $user
   * @return void
   */
  public final function created(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "updated" event.
   *
   * @param User $user
   * @return void
   */
  public final function updated(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "deleted" event.
   *
   * @param User $user
   * @return void
   */
  public final function deleted(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "restored" event.
   *
   * @param User $user
   * @return void
   */
  public final function restored(User $user)
  {
    $this->userRepository->flushCache();
  }

  /**
   * Handle the user "force deleted" event.
   *
   * @param User $user
   * @return void
   */
  public final function forceDeleted(User $user)
  {
    $this->userRepository->flushCache();
  }
}
