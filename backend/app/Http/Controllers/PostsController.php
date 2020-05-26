<?php

namespace App\Http\Controllers;

use App\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{

  public function index()
  {
    return Post::all();
  }

  public function show(Post $post)
  {
    return $post;
  }

  public function store(Request $request)
  {
    $content = $request->validate([
      "name" => "required|string|max:255",
      "description" => "required|string|max:6000"
    ]);

    return Post::create($content);
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
