<?php

namespace App\Http\Resources;

use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed user_name
 * @property mixed created_at
 * @property mixed deleted_at
 * @property mixed email
 * @property mixed updated_at
 * @property mixed name
 */
final class UserResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public final function toArray($request)
  {
    return [
      "id" => $this->id,
      "email" => $this->when($request->user()->hasPermission(Permission::VIEW_USER_EMAIL), $this->email),
      "user_name" => $this->user_name,
      "name" => $this->name,
      "deleted_at" => $this->deleted_at,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
    ];
  }
}
