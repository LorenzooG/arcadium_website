<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
      "user" => new UserResource($this->user),
      "products" => PurchasedProductResource::collection($this->products()->get()),
      "user_name" => $this->user_name,
      "total_price" => floatval($this->total_price),
      "delivered" => $this->delivered,
      "payment_response" => $this->payment_response,
      "payment_type" => $this->payment_type,
      "payment_raw_response" => $this->payment_raw_response,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
    ];
  }
}
