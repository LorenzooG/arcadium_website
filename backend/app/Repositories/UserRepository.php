<?php


namespace App\Repositories;


use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{

  const CACHE_KEY = 'users';

  private CacheRepository $cacheRepository;

  /**
   * UserRepository constructor
   *
   * @param CacheRepository $cacheRepository
   */
  public function __construct(CacheRepository $cacheRepository)
  {
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all users in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public function findAllUsersInPage($page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("all.page.$page"), now()->addHour(), function () {
      return User::query()->paginate();
    });
  }

  /**
   * Find user by it's id
   *
   * @param int $id
   * @return User
   */
  public function findUserById($id)
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
  public function store(array $data)
  {
    return User::create($data);
  }

  /**
   * Forget user from cache
   *
   * @param User $user
   * @return bool
   */
  public function forgetUserFromCache($user)
  {
    return $this->cacheRepository->forget($this->getCacheKey("show.{$user->id}"));
  }

  public final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
