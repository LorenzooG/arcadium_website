<?php


namespace App\Repositories;


use App\User;
use Illuminate\Support\Facades\Cache;

class UserRepository
{

  const CACHE_KEY = 'users';

  public function all($page)
  {
    return Cache::remember($this->getCacheKey("all.page.$page"), now()->addHour(), function () {
      return User::query()->paginate();
    });
  }

  public function show($id)
  {
    return Cache::remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      return User::findOrFail($id);
    });
  }

  public function store(array $data)
  {
  }

  public function update()
  {

  }

  public function delete()
  {

  }

  private final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
