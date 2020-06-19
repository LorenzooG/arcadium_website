<?php

namespace App\Http\Resources;

use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PaymentResource
 *
 * @property Payment $resource
 *
 * @package App\Http\Resources
 */
final class PaymentResource extends JsonResource
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
      'user_name' => $this->resource->user_name,
      'total_paid' => $this->resource->total_paid,
      'total_price' => $this->resource->total_price,
      'is_delivered' => $this->resource->is_delivered,
      'origin_ip_address' => $this->resource->origin_address,
      'payment_method' => $this->resource->payment_method,
      'products' => route('payments.products.index', [
        'payment' => $this->resource->id
      ]),
      'user' => route('users.show', [
        'user' => $this->resource->user_id
      ]),
      'created_at' => $this->resource->created_at,
      'updated_at' => $this->resource->updated_at,
    ];
  }
}
