<?php

namespace App\Http\Resources;

use App\PurchasedProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PurchasedProductResource
 *
 * @property PurchasedProduct $resource
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
      'id' => $this->resource->id,
      'product' => route('products.show', [
        'product' => $this->resource->product_id
      ]),
      'amount' => $this->resource->amount,
    ];
  }
}
