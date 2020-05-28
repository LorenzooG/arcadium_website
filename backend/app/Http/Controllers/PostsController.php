<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLikeRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Post;
use App\User;
use Exception;
use Illuminate\Http\Response;

class PostsController extends Controller
{

  public function index()
  {
    return PostResource::collection(Post::byLikes());
  }

  public function user(User $user)
  {
    return PostResource::collection($user->posts()->orderByDesc('id')->paginate());
  }

  public function show(Post $post)
  {
    return new PostResource($post);
  }

  public function store(PostStoreRequest $request)
  {
    $post = $request->user()->posts()->create($request->only([
      'title',
      'description'
    ]));

    return new PostResource($post);
  }

  public function like(PostLikeRequest $request, Post $post)
  {
    $post->likes()->save($request->user());

    return response()->noContent();
  }

  /**
   * @param Post $post
   * @param PostUpdateRequest $request
   * @return Response
   */
  public function update(Post $post, PostUpdateRequest $request)
  {
    $post->update($request->only([
      'title',
      'description'
    ]));

    return response()->noContent();
  }

  /**
   * @param Post $post
   * @return Response
   * @throws Exception
   */
  public function delete(Post $post)
  {
    $post->delete();

    return response()->noContent();
  }

}
