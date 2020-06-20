<?php

namespace App\Observers;

use App\ProductCommand;
use App\Repositories\ProductCommandRepository;

/**
 * Class ProductCommandObserver
 *
 * @package App\Observers
 */
final class ProductCommandObserver
{
  private ProductCommandRepository $productCommandRepository;

  public function __construct(ProductCommandRepository $productCommandRepository)
  {
    $this->productCommandRepository = $productCommandRepository;
  }

  /**
   * Handle the product command "created" event.
   *
   * @param ProductCommand $productCommand
   * @return void
   */
  public function created(ProductCommand $productCommand)
  {
    $this->productCommandRepository->flushCache();
  }

  /**
   * Handle the product command "updated" event.
   *
   * @param ProductCommand $productCommand
   * @return void
   */
  public function updated(ProductCommand $productCommand)
  {
    $this->productCommandRepository->flushCache();
  }

  /**
   * Handle the product command "deleted" event.
   *
   * @param ProductCommand $productCommand
   * @return void
   */
  public function deleted(ProductCommand $productCommand)
  {
    $this->productCommandRepository->flushCache();
  }
}
