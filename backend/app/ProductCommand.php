<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductCommand
 *
 * @property int id
 * @property string command
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @package App
 */
final class ProductCommand extends Model
{
  protected $fillable = [
    'command'
  ];

  /**
   * Retrieve the product owner of this command
   *
   * @return BelongsTo
   */
  public final function product()
  {
    return $this->belongsTo(Product::class);
  }

}
