<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 *
 * @property int id
 * @property string title
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static News findOrFail($id)
 * @method static News create($data)
 *
 * @package App
 */
class News extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'description'
  ];
}
