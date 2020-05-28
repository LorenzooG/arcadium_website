<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{

  public function index()
  {
    return PostResource::collection(Post::all());
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

  /**
   * @param Post $post
   * @param Request $request
   * @return Response
   */
  public function update(Post $post, Request $request)
  {
    $content = $request->validate([
      "name" => "string|max:255",
      "description" => "string|max:6000"
    ]);

    $post->update($content);

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
