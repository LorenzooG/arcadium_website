<?php


namespace App\Repositories;


use App\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UserRepository
{

  const CACHE_KEY = 'users';

  /**
   * Find all users in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public function findAllUsersInPage($page)
  {
    return Cache::remember($this->getCacheKey("all.page.$page"), now()->addHour(), function () {
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
    return Cache::remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
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
   * Find and update user by it's id
   *
   * @param int $id
   * @param array $data
   * @return bool
   */
  public function updateUserById($id, array $data)
  {
    return $this->findUserById($id)->update($data);
  }

  /**
   * Find and delete user by it's id
   *
   * @param int $id
   * @return bool|null
   * @throws Exception
   */
  public function deleteUserById($id)
  {
    return $this->findUserById($id)->delete();
  }

  private final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
