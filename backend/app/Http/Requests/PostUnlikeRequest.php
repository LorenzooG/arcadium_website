<?php

namespace App\Http\Requests;

use App\Post;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PostUnlikeRequest
 *
 * @property Post post
 *
 * @package App\Http\Requests
 */
class PostUnlikeRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    /* @var User $user */
    $user = $this->user();

    return $this->post->likes()->where('user_id', $user->id)->exists();
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
