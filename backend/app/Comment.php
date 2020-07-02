<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @package App
 *
 * @property int id
 * @property string content
 * @property Post post
 * @property User user
 * @property int user_id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static Comment create(array $array)
 * @method static Comment findOrFail(int $int)
 */
final class Comment extends Pivot
{

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'comments';

  /**
   * Indicates if the IDs are auto-incrementing.
   *
   * @var bool
   */
  public $incrementing = true;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'content',
    'user_id',
    'post_id'
  ];

  /**
   * User owner of this comment
   *
   * @return BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
