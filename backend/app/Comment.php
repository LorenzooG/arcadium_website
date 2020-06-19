<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
final class Comment extends Model
{
  protected $fillable = [
    'content',
    'user_id',
    'post_id'
  ];

  /**
   * Retrieve the user owner of this comment
   *
   * @return BelongsTo
   */
  public final function user()
  {
    return $this->belongsTo(User::class);
  }


  /**
   * Retrieve the post owner of this comment
   *
   * @return BelongsTo
   */
  public final function post()
  {
    return $this->belongsTo(Post::class);
  }
}
