<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
    'command',
    'product_id'
  ];
}
