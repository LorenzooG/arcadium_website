<?php

namespace App\Observers;

use App\Comment;
use App\Repositories\CommentRepository;

class CommentObserver
{
  private CommentRepository $commentRepository;

  public function __construct(CommentRepository $commentRepository)
  {
    $this->commentRepository = $commentRepository;
  }

  /**
   * Handle the comment "created" event.
   *
   * @param Comment $comment
   * @return void
   */
  public function created(Comment $comment)
  {
    $this->commentRepository->flushCache();
  }

  /**
   * Handle the comment "updated" event.
   *
   * @param Comment $comment
   * @return void
   */
  public function updated(Comment $comment)
  {
    $this->commentRepository->flushCache();
  }

  /**
   * Handle the comment "deleted" event.
   *
   * @param Comment $comment
   * @return void
   */
  public function deleted(Comment $comment)
  {
    $this->commentRepository->flushCache();
  }

  /**
   * Handle the comment "restored" event.
   *
   * @param Comment $comment
   * @return void
   */
  public function restored(Comment $comment)
  {
    $this->commentRepository->flushCache();
  }

  /**
   * Handle the comment "force deleted" event.
   *
   * @param Comment $comment
   * @return void
   */
  public function forceDeleted(Comment $comment)
  {
    $this->commentRepository->flushCache();
  }
}
