<?php

namespace App\Http\Requests;

use App\EmailUpdate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserUpdateEmailRequest
 *
 * @property EmailUpdate email_update
 *
 * @package App\Http\Requests
 */
final class UserUpdateEmailRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public final function authorize()
  {
    return $this->email_update->isValid();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public final function rules()
  {
    return [
      'new_email' => 'required|string|min:8|max:48'
    ];
  }
}
