<?php


namespace App\Repositories;


use App\Role;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class RoleRepository
{

  const CACHE_KEY = 'roles';

  private CacheRepository $cacheRepository;

  /**
   * RoleRepository constructor
   *
   * @param CacheRepository $cacheRepository
   */
  public function __construct(CacheRepository $cacheRepository)
  {
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all roles in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public function findAllRolesInPage($page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("all.pages.$page"), now()->addHour(), function () {
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
  public function findAllRolesOfUserInPage($user, $page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("user.{$user->id}.roles.$page"), now()->addHour(), function () use ($user) {
      return $user->roles()->paginate();
    });
  }

  /**
   * Store role in database
   *
   * @param array $data
   * @return Model
   */
  public function store(array $data)
  {
    return Role::create($data);
  }

  /**
   * Find role by it's id
   *
   * @param int $id
   * @return User
   */
  public function findRoleById($id)
  {
    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      return Role::findOrFail($id);
    });
  }

  /**
   * Forget role from cache
   *
   * @param Role $role
   * @return bool
   */
  public function forgetRoleFromCache($role)
  {
    return $this->cacheRepository->forget($this->getCacheKey("show.{$role->id}"));
  }


  /**
   * Forget all roles from the cache
   *
   * @return bool
   */
  public function forgetAllRolesFromCache()
  {
    return $this->cacheRepository->forget($this->getCacheKey("all.*"));
  }

  /**
   * Forget all user's roles from the cache
   *
   * @param User $user
   * @return bool
   */
  public function forgetAllUserRolesFromCache($user)
  {
    return $this->cacheRepository->forget($this->getCacheKey("user.{$user->id}.roles.*"));
  }


  public final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }

}
