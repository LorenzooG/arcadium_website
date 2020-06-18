<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @package App
 *
 * @property int id
 * @property int amount
 * @property Payment payment
 * @property Product product
 *
 * @method static PurchasedProduct create(array $array)
 * @method static PurchasedProduct findOrFail(int $int)
 *
 */
final class PurchasedProduct extends Pivot
{

  protected $table = 'purchased_products';

  protected $fillable = [
    'amount'
  ];

  public final function product()
  {
    return $this->belongsTo(Product::class);
  }

  public final function payment()
  {
    return $this->belongsTo(Payment::class);
  }
}
