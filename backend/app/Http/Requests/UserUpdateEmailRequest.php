<?php

namespace App\Http\Requests;

use App\Utils\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateEmailRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return !$this->email_update->already_used
      && $this->user()->hasPermission(Permission::UPDATE_USER);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
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
