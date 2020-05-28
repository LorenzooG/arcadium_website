<?php


namespace App\Repositories;


use App\Post;
use App\User;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{

  const CACHE_KEY = 'posts';

  private Repository $cacheRepository;

  /**
   * UserRepository constructor
   *
   * @param Repository $cacheRepository
   */
  public function __construct(Repository $cacheRepository)
  {
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all posts in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public function findAllPostsInPage($page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("all.page.$page"), now()->addHour(), function () {
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
  public function findAllPostsOfUserInPage($user, $page)
  {
    return $this->cacheRepository->remember($this->getCacheKey("all.page.$page"), now()->addHour(), function () use ($user) {
      return $user->posts()->orderByDesc('id')->paginate();
    });
  }

  /**
   * Find post by it's id
   *
   * @param int $id
   * @return Post
   */
  public function findPostById($id)
  {
    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      return Post::findOrFail($id);
    });
  }

  /**
   * Store post in database
   *
   * @param User $user
   * @param array $data
   * @return Model
   */
  public function store(User $user, array $data)
  {
    return $user->posts()->create($data);
  }

  public final function getCacheKey($key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
