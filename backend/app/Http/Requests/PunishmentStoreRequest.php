<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PunishmentStoreRequest extends FormRequest
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
      'punished_user_name' => 'required|string|max:32',
      'punished_at' => 'required|numeric',
      'punished_until' => 'required|numeric',
      'proof' => 'required|string|max:340',
      'reason' => 'required|string|max:340',
      'punished_by' => 'required|string|max:32',
    ];
  }
}
