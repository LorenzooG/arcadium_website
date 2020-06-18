<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'purchased_products';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'amount',
    'product_id'
  ];

  /**
   * Retrieve the product owner of this purchased product
   *
   * @return BelongsTo
   */
  public final function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Retrieve the payment owner of this purchased product
   *
   * @return BelongsTo
   */
  public final function payment()
  {
    return $this->belongsTo(Payment::class);
  }
}
