<?php

namespace App\Http\Resources;

use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @package App
 *
 * @property int id
 * @property string title
 * @property int permission_level
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class RoleResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'permission_level' => $this->when($request->user()->hasPermission(Permission::VIEW_ROLES_PERMISSIONS), $this->permission_level),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
  }
}
