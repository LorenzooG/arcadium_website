<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
      "id" => $this->id,
      "name" => $this->name,
      "description" => $this->description,
      "price" => $this->price,
      "image" => url("api/v1/products/1/image"),
      "commands" => $this->when($request->user() && $request->user()->isAdmin,
        $this->commands()->get()->map(function ($command) {
          return $command->command;
        })),
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
    ];
  }
}
