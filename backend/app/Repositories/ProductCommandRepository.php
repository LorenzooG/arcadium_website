<?php


namespace App\Repositories;


use App\Post;
use App\Product;
use App\ProductCommand;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Logger;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ProductCommandRepository
 *
 * @package App\Repositories
 */
final class ProductCommandRepository
{

  const CACHE_KEY = 'products_commands';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * ProductCommandRepository constructor
   *
   * @param Logger $logger
   * @param CacheRepository $cacheRepository
   */
  public final function __construct(Logger $logger, CacheRepository $cacheRepository)
  {
    $this->logger = $logger;
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all product's commands in a page
   *
   * @param Product $product
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedProductCommandsForProduct($product, $page)
  {
    $this->logger->info("Retrieving product {$product->id}'s commands in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("for.$product.paginated.$page"), now()->addHour(), function () use ($product, $page) {
      $this->logger->info("Caching product {$product->id}'s commands in page {$page}.");

      return $product->commands()->paginate();
    });
  }

  /**
   * Find product command by it's id
   *
   * @param int $id
   * @return Post
   */
  public final function findProductCommandById($id)
  {
    $this->logger->info("Retrieving product command {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching product command {$id}.");

      return ProductCommand::findOrFail($id);
    });
  }

  /**
   * Create product command in database
   *
   * @param Product $product
   * @param array $data
   * @return Model
   */
  public final function createProductCommand($product, array $data)
  {
    $this->logger->info("Creating command for product {$product->id}.");

    return $product->commands()->create($data);
  }

  /**
   * Remove all keys from cache
   *
   * @return void
   */
  public final function flushCache()
  {
    $this->logger->info("Flushing cache for key {$this->getCacheKey('*')}.");

    $this->cacheRepository->getStore()->flush();
  }

  /**
   * Return cache key for post repository
   *
   * @param string $key
   * @return string
   */
  public final function getCacheKey(string $key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
