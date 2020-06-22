<?php


namespace App\Http\Controllers;


use App\Http\Requests\NewsStoreRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Http\Resources\NewsResource;
use App\News;
use App\Repositories\NewsRepository;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

final class NewsController extends Controller
{

  private NewsRepository $newsRepository;

  /**
   * NewsController constructor
   *
   * @param NewsRepository $newsRepository
   */
  public final function __construct(NewsRepository $newsRepository)
  {
    $this->newsRepository = $newsRepository;
  }

  /**
   * Find and show paginated news
   *
   * @return ResourceCollection
   */
  public final function index()
  {
    $page = Paginator::resolveCurrentPage();

    return NewsResource::collection($this->newsRepository->findPaginatedNews($page));
  }

  /**
   * Find and show news
   *
   * @param News $news
   * @return NewsResource
   */
  public final function show(News $news)
  {
    return new NewsResource($news);
  }

  /**
   * Store news in database
   *
   * @param NewsStoreRequest $request
   * @return NewsResource
   */
  public final function store(NewsStoreRequest $request)
  {
    $news = $this->newsRepository->createNews($request->only([
      'title',
      'description'
    ]));

    return new NewsResource($news);
  }

  /**
   * Find and update a news
   *
   * @param News $news
   * @param NewsUpdateRequest $request
   * @return Response
   */
  public final function update(News $news, NewsUpdateRequest $request)
  {
    $news->update($request->only([
      'title',
      'description'
    ]));

    return response()->noContent();
  }

  /**
   * Find and delete a news
   *
   * @param News $news
   * @return Response
   * @throws Exception
   */
  public final function delete(News $news) {
    $news->delete();

    return response()->noContent();
  }

}
