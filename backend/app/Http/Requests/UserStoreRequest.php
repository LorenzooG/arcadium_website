<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UserStoreRequest extends FormRequest
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
      'email' => 'required|string|min:8|max:48|unique:users',
      'user_name' => 'required|string|min:8|max:32',
      'name' => 'required|string|min:3|max:32',
      'password' => 'required|string|min:8|max:16',
    ];
  }
}
