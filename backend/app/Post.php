<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

/**
 * @package App
 *
 * @property int id
 * @property string title
 * @property string description
 * @property User user
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection<User> likes
 *
 * @method static Post create(array $array)
 * @method static Post findOrFail(int $int)
 */
final class Post extends Model
{
  protected $fillable = [
    'title',
    'description'
  ];

  /**
   * Retrieve the user owner of this post
   *
   * @return BelongsTo
   */
  public final function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Retrieve the likes of this post
   *
   * @return BelongsToMany
   */
  public final function likes()
  {
    return $this->belongsToMany(User::class);
  }

  /**
   * Retrieve the comments of this post
   *
   * @return HasMany
   */
  public final function comments()
  {
    return $this->hasMany(Comment::class);
  }

  /**
   * Retrieve paginated posts ordered by the like count
   *
   * @return Builder
   */
  public static function byLikes()
  {
    return self::query()->selectRaw('posts.*, count(*) as total')
      ->join('post_user', 'posts.id', '=', 'post_user.post_id')
      ->groupBy('posts.id')
      ->orderByDesc('total');
  }
}
