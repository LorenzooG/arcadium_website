<?php

namespace App\Http\Resources;

use App\ProductCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductCommandResource
 *
 * @property ProductCommand resource
 *
 * @package App\Http\Resources
 */
class ProductCommandResource extends JsonResource
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
      'id' => $this->resource->id,
      'command' => $this->resource->command,
      'created_at' => $this->resource->created_at,
      'updated_at' => $this->resource->updated_at,
    ];
  }
}
