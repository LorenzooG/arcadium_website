<?php

namespace App\Observers;

use App\News;
use App\Repositories\NewsRepository;

final class NewsObserver
{
  private NewsRepository $newsRepository;

  /**
   * NewsObserver constructor
   *
   * @param NewsRepository $newsRepository
   */
  public function __construct(NewsRepository $newsRepository)
  {
    $this->newsRepository = $newsRepository;
  }

  /**
   * Handle the news "created" event.
   *
   * @param News $news
   * @return void
   */
  public function created(News $news)
  {
    $this->newsRepository->flushCache();
  }

  /**
   * Handle the news "updated" event.
   *
   * @param News $news
   * @return void
   */
  public function updated(News $news)
  {
    $this->newsRepository->flushCache();
  }

  /**
   * Handle the news "deleted" event.
   *
   * @param News $news
   * @return void
   */
  public function deleted(News $news)
  {
    $this->newsRepository->flushCache();
  }
}
