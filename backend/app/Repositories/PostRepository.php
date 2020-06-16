<?php


namespace App\Repositories;


use App\Post;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Logger;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PostRepository
 *
 * @package App\Repositories
 */
final class PostRepository
{

  const CACHE_KEY = 'posts';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * PostRepository constructor
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
   * Find all posts in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedPosts($page)
  {
    $this->logger->info("Retrieving posts in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching posts in page {$page}.");

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
    $this->logger->info("Retrieving user {$user->id}'s posts in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("for.$user.paginated.$page"), now()->addHour(), function () use ($user, $page) {
      $this->logger->info("Caching user {$user->id}'s posts in page {$page}.");

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
    $this->logger->info("Retrieving post {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching post {$id}.");

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
    $this->logger->info("Creating post for user {$user->id}.");

    return $user->posts()->create($data);
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
