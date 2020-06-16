<?php

namespace App\Observers;

use App\Post;
use App\Repositories\PostRepository;

final class PostObserver
{
  private PostRepository $postRepository;

  public final function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  /**
   * Handle the post "created" event.
   *
   * @param Post $post
   * @return void
   */
  public final function created(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "updated" event.
   *
   * @param Post $post
   * @return void
   */
  public final function updated(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "deleted" event.
   *
   * @param Post $post
   * @return void
   */
  public final function deleted(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "restored" event.
   *
   * @param Post $post
   * @return void
   */
  public final function restored(Post $post)
  {
    $this->postRepository->flushCache();
  }

  /**
   * Handle the post "force deleted" event.
   *
   * @param Post $post
   * @return void
   */
  public final function forceDeleted(Post $post)
  {
    $this->postRepository->flushCache();
  }
}
