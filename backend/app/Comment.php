<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 *
 * @property int id
 * @property string content
 * @property Post post
 * @property User user
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static Comment create(array $array)
 * @method static Comment findOrFail(int $int)
 */
class Comment extends Model
{
  protected $fillable = [
    'content',
    'user_id',
    'post_id'
  ];
}
