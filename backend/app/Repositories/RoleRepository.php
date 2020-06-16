<?php


namespace App\Repositories;


use App\Role;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class RoleRepository
{

  const CACHE_KEY = 'roles';

  private CacheRepository $cacheRepository;

  /**
   * RoleRepository constructor
   *
   * @param CacheRepository $cacheRepository
   */
  public final function __construct(CacheRepository $cacheRepository)
  {
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
    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () {
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
    return $this->cacheRepository->remember($this->getCacheKey("for.$user.paginated.$page"), now()->addHour(), function () use ($user) {
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
    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
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
