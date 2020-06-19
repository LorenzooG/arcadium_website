<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CommentUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public final function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public final function rules()
  {
    return [
      'content' => 'string|max:140'
    ];
  }
}
