<?php


namespace App\Repositories;

use App\News;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Log\Logger;
use Illuminate\Pagination\LengthAwarePaginator;

final class NewsRepository
{

  const CACHE_KEY = 'news';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * NewRepository constructor
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
   * Find all news in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedNews(int $page)
  {
    $this->logger->info("Retrieving news in page $page.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching news in page {$page}.");

      return News::query()->orderByDesc('id')->paginate();
    });
  }

  /**
   * Find new by it's id
   *
   * @param int $id
   * @return News
   */
  public final function findNewsById($id)
  {
    $this->logger->info("Retrieving comment {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching comment {$id}.");

      return News::findOrFail($id);
    });
  }

  /**
   * Create new in database
   *
   * @param array $data
   * @return News
   */
  public final function createNews(array $data)
  {
    $this->logger->info("Creating new with title {$data['title']}.");

    return News::create($data);
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
