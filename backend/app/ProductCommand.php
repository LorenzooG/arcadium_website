<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ProductCommand extends Model
{
  protected $fillable = [
    "command"
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
