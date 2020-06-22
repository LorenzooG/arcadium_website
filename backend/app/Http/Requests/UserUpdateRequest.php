<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UserUpdateRequest extends FormRequest
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
      'email' => 'string|min:8|max:48|unique:users',
      'user_name' => 'string|min:8|max:32',
      'name' => 'string|min:3|max:32',
      'password' => 'string|min:8|max:16',
    ];
  }
}
