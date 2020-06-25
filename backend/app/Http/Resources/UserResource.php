<?php

namespace App\Http\Resources;

use App\User;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @property User resource
 *
 * @package App\Http\Resources
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
      'id' => $this->resource->id,
      'email' => $this->when($request->user()->hasPermission(Permission::VIEW_USER_EMAIL), $this->email),
      'user_name' => $this->resource->user_name,
      'name' => $this->resource->name,
      'posts' => route('users.posts.index', [
        'user' => $this->resource->id
      ]),
      'roles' => route('users.roles.index', [
        'user' => $this->resource->id
      ]),
      'avatar' => route('users.avatar', [
        'user' => $this->resource->id
      ]),
      'email_verified_at' => $this->resource->email_verified_at,
      'deleted_at' => $this->resource->deleted_at,
      'created_at' => $this->resource->created_at,
      'updated_at' => $this->resource->updated_at
    ];
  }
}
