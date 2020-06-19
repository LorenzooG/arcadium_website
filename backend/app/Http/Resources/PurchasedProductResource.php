<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PurchasedProductResource
 *
 * @property Product $resource
 *
 * @package App\Http\Resources
 */
final class PurchasedProductResource extends JsonResource
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
      'product' => route('products.show', [
        'product' => $this->resource->pivot->product_id
      ]),
      'amount' => $this->resource->pivot->amount,
    ];
  }
}
