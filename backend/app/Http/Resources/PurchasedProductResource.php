<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Throwable;

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
    $product = $this->product_id;

    try {
      $product = new ProductResource(Product::findOrFail($product));
    } catch (Throwable $exception) {
    }

    return [
      "product" => $product,
      "amount" => $this->amount
    ];
  }
}
