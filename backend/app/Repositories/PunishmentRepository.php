<?php


namespace App\Repositories;

use App\Punishment;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Log\Logger;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PunishmentRepository
 *
 * @package App\Repositories
 */
final class PunishmentRepository
{

  const CACHE_KEY = 'punishments';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * PunishmentRepository constructor
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
   * Find all punishments in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedPunishments($page)
  {
    $this->logger->info("Retrieving punishments in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching punishments in page {$page}.");

      return Punishment::query()->orderByDesc('id')->paginate();
    });
  }

  /**
   * Find punishment by it's id
   *
   * @param int $id
   * @return Punishment
   */
  public final function findPunishmentById($id)
  {
    $this->logger->info("Retrieving punishment {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching punishment {$id}.");

      return Punishment::findOrFail($id);
    });
  }

  /**
   * Create punishment in database
   *
   * @param array $data
   * @return Punishment
   */
  public final function createPunishment(array $data)
  {
    $this->logger->info("Creating punishment for punished user name: {$data['punished_user_name']}.");

    return Punishment::create($data);
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
