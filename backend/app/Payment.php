<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @package App
 *
 * @property int id
 * @property string user_name
 * @property boolean is_delivered
 * @property string payment_method
 * @property string origin_address
 * @property double total_price
 * @property double total_paid
 * @property User user
 *
 * @method static Payment create(array $array)
 * @method static Payment findOrFail(int $int)
 */
final class Payment extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'payment_method',
    'is_delivered',
    'user_name',
    'origin_address',
    'total_paid',
    'total_price'
  ];

  /**
   * Retrieve the user owner of this payment
   *
   * @return BelongsTo
   */
  public final function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Retrieve the products of this payment
   *
   * @return BelongsToMany
   */
  public final function products()
  {
    return $this->belongsToMany(Product::class, 'purchased_products')
      ->using(PurchasedProduct::class);
  }

}
