<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

final class ProductCommand extends Model
{
  protected $fillable = [
    "command"
  ];

  public final function product()
  {
    return $this->belongsTo(Product::class);
  }

}
