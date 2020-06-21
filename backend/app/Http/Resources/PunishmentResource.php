<?php

namespace App\Http\Resources;

use App\Punishment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PunishmentResource
 *
 * @property Punishment $resource
 *
 * @package App\Http\Resources
 */
final class PunishmentResource extends JsonResource
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
      'punished_user_name' => $this->resource->punished_user_name,
      'reason' => $this->resource->reason,
      'proof' => $this->resource->proof,
      'punishment_duration' => Carbon::createFromTimestampMs($this->resource->punishment_duration),
      'punished_until' => Carbon::createFromTimestampMs($this->resource->punished_until),
      'punished_at' => Carbon::createFromTimestampMs($this->resource->punished_at),
      'created_at' => $this->resource->created_at,
      'updated_at' => $this->resource->updated_at,
    ];
  }
}
