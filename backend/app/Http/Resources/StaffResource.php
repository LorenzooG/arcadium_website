<?php

namespace App\Http\Resources;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class StaffResource
 *
 * @property Role $resource
 *
 * @package App\Http\Resources
 */
class StaffResource extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'i'
    ];
  }
}
