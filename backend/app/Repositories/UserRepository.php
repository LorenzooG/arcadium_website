<?php


namespace App\Repositories;


use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserRepository
{

  const CACHE_KEY = 'users';

  private CacheRepository $cacheRepository;

  /**
   * UserRepository constructor
   *
   * @param CacheRepository $cacheRepository
   */
  public final function __construct(CacheRepository $cacheRepository)
  {
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all users in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedUsers($page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () {
      return User::query()->paginate();
    });
  }

  /**
   * Find user by it's id
   *
   * @param int $id
   * @return User
   */
  public final function findUserById($id)
  {
    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      return User::findOrFail($id);
    });
  }

  /**
   * Store user in database
   *
   * @param array $data
   * @return User
   */
  public final function createUser(array $data)
  {
    return User::create($data);
  }

  /**
   * Remove all keys from cache
   *
   * @return void
   */
  public final function flushCache()
  {
    $this->cacheRepository->getStore()->flush();
  }

  /**
   * Return cache key for user repository
   *
   * @param string $key
   * @return string
   */
  public final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
