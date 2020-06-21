<?php

namespace App\Observers;

use App\Punishment;
use App\Repositories\PunishmentRepository;

final class PunishmentObserver
{
  private PunishmentRepository $punishmentRepository;

  /**
   * PunishmentObserver constructor
   *
   * @param PunishmentRepository $punishmentRepository
   */
  public final function __construct(PunishmentRepository $punishmentRepository)
  {
    $this->punishmentRepository = $punishmentRepository;
  }

  /**
   * Handle the punishment "created" event.
   *
   * @param Punishment $punishment
   * @return void
   */
  public function created(Punishment $punishment)
  {
    $this->punishmentRepository->flushCache();
  }

  /**
   * Handle the punishment "updated" event.
   *
   * @param Punishment $punishment
   * @return void
   */
  public function updated(Punishment $punishment)
  {
    $this->punishmentRepository->flushCache();
  }

  /**
   * Handle the punishment "deleted" event.
   *
   * @param Punishment $punishment
   * @return void
   */
  public function deleted(Punishment $punishment)
  {
    $this->punishmentRepository->flushCache();
  }
}
