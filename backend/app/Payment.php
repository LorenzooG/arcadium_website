<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 *
 * @property int id
 * @property string user_name
 * @property boolean delivered
 * @property string payment_type
 * @property boolean payment_response
 * @property string payment_raw_response
 * @property double total_price
 *
 * @method static Payment create(array $array)
 * @method static Payment findOrFail(int $int)
 */
final class Payment extends Model
{

  protected $fillable = [
    'payment_method',
    'delivered',
    'user_name',
    'origin_address',
    'total_paid',
    'total_price'
  ];

  protected $casts = [
    'delivered' => 'boolean',
  ];

  public final function user()
  {
    return $this->belongsTo(User::class);
  }

  public final function products()
  {
    return $this->belongsToMany(Product::class, 'purchased_products');
  }

}
