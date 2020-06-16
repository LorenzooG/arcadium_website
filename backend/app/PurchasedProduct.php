<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

  /**
   * Retrieve the product owner of this purchased product entity
   *
   * @return BelongsTo
   */
  public final function product()
  {
    return $this->belongsTo(Product::class);
  }
}
