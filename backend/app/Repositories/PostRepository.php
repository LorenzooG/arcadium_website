<?php


namespace App\Repositories;


use App\Post;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

final class PostRepository
{

  const CACHE_KEY = 'posts';

  private CacheRepository $cacheRepository;

  /**
   * PostRepository constructor
   *
   * @param CacheRepository $cacheRepository
   */
  public final function __construct(CacheRepository $cacheRepository)
  {
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all posts in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedPosts($page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () {
      return Post::byLikes()->paginate();
    });
  }

  /**
   * Find all user's posts in a page
   *
   * @param User $user
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedPostsForUser($user, $page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("for.$user.paginated.$page"), now()->addHour(), function () use ($user) {
      return $user->posts()->orderByDesc('id')->paginate();
    });
  }

  /**
   * Find post by it's id
   *
   * @param int $id
   * @return Post
   */
  public final function findPostById($id)
  {
    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      return Post::findOrFail($id);
    });
  }

  /**
   * Create post in database
   *
   * @param User $user
   * @param array $data
   * @return Model
   */
  public final function createPost(User $user, array $data)
  {
    return $user->posts()->create($data);
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
