<?php

namespace App\Observers;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Class UserObserver
 *
 * @package App\Observers
 */
final class UserObserver
{

  private UserRepository $userRepository;
  private FilesystemManager $storage;

  /**
   * UserObserver constructor
   *
   * @param UserRepository $userRepository
   * @param FilesystemManager $storage
   */
  public final function __construct(UserRepository $userRepository, FilesystemManager $storage)
  {
    $this->userRepository = $userRepository;
    $this->storage = $storage;
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
}
