<?php

namespace App\Observers;

use App\Product;
use App\Repositories\ProductRepository;

class ProductObserver
{

  private ProductRepository $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  /**
   * Handle the product "created" event.
   *
   * @param Product $product
   * @return void
   */
  public function created(Product $product)
  {
    $this->productRepository->flushCache();
  }

  /**
   * Handle the product "updated" event.
   *
   * @param Product $product
   * @return void
   */
  public function updated(Product $product)
  {
    $this->productRepository->flushCache();
  }

  /**
   * Handle the product "deleted" event.
   *
   * @param Product $product
   * @return void
   */
  public function deleted(Product $product)
  {
    $this->productRepository->flushCache();
  }
}
