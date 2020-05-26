<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCommand extends Model
{
  protected $fillable = [
    "command"
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

}
