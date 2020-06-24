<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Post;
use App\Repositories\CommentRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

final class CommentController extends Controller
{
  private CommentRepository $commentRepository;

  /**
   * CommentController constructor
   *
   * @param $commentRepository
   */
  public function __construct(CommentRepository $commentRepository)
  {
    $this->commentRepository = $commentRepository;
  }

  /**
   * Find and show all comments from a post paginated
   *
   * @param Post $post
   *
   * @return AnonymousResourceCollection
   */
  public function post(Post $post)
  {
    $page = Paginator::resolveCurrentPage();

    return CommentResource::collection($this->commentRepository->findPaginatedCommentsForPost($post, $page));
  }

  /**
   * Store comment in database
   *
   * @param Post $post
   * @param CommentStoreRequest $request
   *
   * @return CommentResource
   */
  public function store(Post $post, CommentStoreRequest $request)
  {
    $comment = $this->commentRepository->createComment($request->user(), $post, [
      'content' => $request->json('content')
    ]);

    return new CommentResource($comment);
  }

  /**
   * Find and update comment
   *
   * @param Comment $comment
   * @param CommentUpdateRequest $request
   *
   * @return Response
   */
  public function update(Comment $comment, CommentUpdateRequest $request)
  {
    $comment->update([
      'content' => $request->json('content'),
      'updated' => true
    ]);

    return response()->noContent();
  }

  /**
   * Find and delete comment
   *
   * @param Comment $comment
   *
   * @throws Exception
   *
   * @return Response
   */
  public function delete(Comment $comment)
  {
    $comment->delete();

    return response()->noContent();
  }
}
