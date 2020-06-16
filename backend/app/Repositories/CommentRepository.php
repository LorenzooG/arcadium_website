<?php


namespace App\Repositories;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

final class CommentRepository
{

  const CACHE_KEY = 'comments';

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
   * Find all posts's comments in a page
   *
   * @param Post $post
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedCommentsForPost($post, $page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("for.$post.paginated.$page"), now()->addHour(), function () use ($post) {
      return $post->comments()->paginate();
    });
  }

  /**
   * Find comment by it's id
   *
   * @param int $id
   * @return Post
   */
  public final function findCommentById($id)
  {
    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      return Comment::findOrFail($id);
    });
  }

  /**
   * Create comment in database
   *
   * @param User $user
   * @param Post $post
   * @param array $data
   * @return Model
   */
  public final function createComment(User $user, Post $post, array $data)
  {
    $data['user_id'] = $user->id;
    $data['post_id'] = $post->id;

    return Comment::create($data);
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
