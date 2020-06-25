<?php

namespace App\Http\Requests;

use App\Repositories\Tokens\EmailResetTokenRepository;
use Illuminate\Foundation\Http\FormRequest;

class EmailResetRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    /** @var EmailResetTokenRepository $repository */
    $repository = resolve(EmailResetTokenRepository::class);

    return $repository->recentlyCreatedToken($this->user());
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
