<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
class Post extends Model
{
  protected $fillable = [
    'title',
    'description'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function likes()
  {
    return $this->belongsToMany(User::class);
  }
}
