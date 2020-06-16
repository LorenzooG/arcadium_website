<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 *
 * @property int id
 * @property int amount
 * @property Product product
 *
 * @method static PurchasedProduct create(array $array)
 * @method static PurchasedProduct findOrFail(int $int)
 *
 */
final class PurchasedProduct extends Model
{

  protected $fillable = [
    "product_id",
    "amount",
  ];

  public final function product()
  {
    return $this->belongsTo(Product::class);
  }
}
