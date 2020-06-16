<?php


namespace App\Repositories;


use App\Role;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Logger;

/**
 * Class RoleRepository
 *
 * @package App\Repositories
 */
final class RoleRepository
{

  const CACHE_KEY = 'roles';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * RoleRepository constructor
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
   * Find all roles in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedRoles($page)
  {
    $this->logger->info("Retrieving roles in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching roles in page {$page}.");

      return Role::query()->paginate();
    });
  }

  /**
   * Find all user's roles in a page
   *
   * @param User $user
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedRolesForUser($user, $page)
  {
    $this->logger->info("Retrieving user {$user->id}'s roles in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("for.$user.paginated.$page"), now()->addHour(), function () use ($user, $page) {
      $this->logger->info("Caching user {$user->id}'s roles in page {$page}.");

      return $user->roles()->paginate();
    });
  }

  /**
   * Store role in database
   *
   * @param array $data
   * @return Model
   */
  public final function createRole(array $data)
  {
    $this->logger->info("Creating role with title {$data['title']}.");

    return Role::create($data);
  }

  /**
   * Find role by it's id
   *
   * @param int $id
   * @return User
   */
  public final function findRoleById($id)
  {
    $this->logger->info("Retrieving role {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching role {$id}.");

      return Role::findOrFail($id);
    });
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
   * Return cache key for role repository
   *
   * @param string $key
   * @return string
   */
  public final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }

}
