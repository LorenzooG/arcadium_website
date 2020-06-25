<?php

namespace App\Http\Resources;

use App\Product;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 *
 * @property Product resource
 *
 * @package App\Http\Resources
 */
final class ProductResource extends JsonResource
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
      'title' => $this->resource->title,
      'description' => $this->resource->description,
      'price' => $this->resource->price,
      'image' => route('products.image', [
        'product' => $this->resource->id
      ]),
      'commands' => $this->when($request->user()->hasPermission(Permission::VIEW_PRODUCT_COMMANDS),
        route('products.commands.index', [
          'product' => $this->resource->id
        ])),
      'created_at' => $this->resource->created_at,
      'updated_at' => $this->resource->updated_at
    ];
  }
}
