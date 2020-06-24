<?php


namespace App\Repositories;


use App\Product;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Log\Logger;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ProductRepository
 *
 * @package App\Repositories
 */
final class ProductRepository
{

  const CACHE_KEY = 'products';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * ProductRepository constructor
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
   * Find all products in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedProducts($page)
  {
    $this->logger->info("Retrieving products in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching products in page {$page}.");

      return Product::query()->paginate();
    });
  }

  /**
   * Find all trashed products in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedTrashedProducts($page)
  {
    $this->logger->info("Retrieving trashed products in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching trashed products in page {$page}.");

      return Product::onlyTrashed()->paginate();
    });
  }

  /**
   * Find post by it's id
   *
   * @param int $id
   * @return Product
   */
  public final function findProductById($id)
  {
    $this->logger->info("Retrieving product {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching product {$id}.");

      return Product::withTrashed()->findOrFail($id);
    });
  }

  /**
   * Create product in database
   *
   * @param array $data
   * @return Product
   */
  public final function createProduct(array $data)
  {
    $this->logger->info("Creating product.");

    return Product::create($data);
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
