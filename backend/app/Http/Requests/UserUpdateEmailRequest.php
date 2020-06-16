<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UserUpdateEmailRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public final function authorize()
  {
    return !$this->email_update->already_used;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public final function rules()
  {
    return [
      'new_email' => [
        'required',
        'string',
        'min:8',
        'max:32',
        Rule::unique('users', 'email')->ignore($this->email_update->user->id)
      ]
    ];
  }
}
