<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PunishmentUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'punished_user_name' => 'string|max:32',
      'punished_at' => 'numeric',
      'punished_until' => 'numeric',
      'proof' => 'string|max:340',
      'reason' => 'string|max:340',
      'punished_by' => 'string|max:32',
    ];
  }
}
