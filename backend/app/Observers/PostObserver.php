<?php

namespace App\Observers;

use App\Post;
use App\Repositories\PostRepository;

class PostObserver
{
  private PostRepository $postRepository;

  public function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  /**
   * Handle the post "created" event.
   *
   * @param Post $post
   * @return void
   */
  public function created(Post $post)
  {
    $this->postRepository->updatePostInTheCache($post);
  }

  /**
   * Handle the post "updated" event.
   *
   * @param Post $post
   * @return void
   */
  public function updated(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "deleted" event.
   *
   * @param Post $post
   * @return void
   */
  public function deleted(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "restored" event.
   *
   * @param Post $post
   * @return void
   */
  public function restored(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "force deleted" event.
   *
   * @param Post $post
   * @return void
   */
  public function forceDeleted(Post $post)
  {
    $this->postRepository->flushCache();
  }
}
