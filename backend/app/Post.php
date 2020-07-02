<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property mixed pivot
 *
 * @method static Post create(array $array)
 * @method static Post findOrFail(int $int)
 */
final class Post extends Model
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
   * @return BelongsToMany
   */
  public final function comments()
  {
    return $this->belongsToMany(User::class, 'comments')
      ->using(Comment::class)
      ->withTimestamps()
      ->withPivot([
        'id',
        'content'
      ]);
  }

  /**
   * Retrieve paginated posts ordered by the like count
   *
   * @return Builder
   */
  public static function byLikes()
  {
    // TODO: Solve error that are not showing posts that haven't likes
    return self::query()->selectRaw('posts.*, count(*) as total')
      ->join('post_user', 'posts.id', '=', 'post_user.post_id')
      ->groupBy('posts.id')
      ->orderByDesc('total');
  }
}
