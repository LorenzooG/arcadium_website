<?php

namespace App\Http\Requests;

use App\Post;
use App\Repositories\PostRepository;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class PostLikeRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    /** @var PostRepository $postRepository */
    $postRepository = resolve(PostRepository::class);

    $post = $postRepository->findPostById($this->route('post'));
    /* @var User $user */
    $user = $this->user();

    return $post->likes()->where('user_id', $user->id)->doesntExist();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      //
    ];
  }
}
