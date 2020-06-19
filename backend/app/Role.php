<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @package App
 *
 * @property int id
 * @property string title
 * @property int permission_level
 * @property Collection<User> users
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static Role create(array $array)
 * @method static Role findOrFail(int $int)
 *
 */
final class Role extends Model
{
  protected $fillable = [
    'title',
    'color',
    'permission_level'
  ];

  /**
   * Retrieve the users that have this role
   *
   * @return BelongsToMany
   */
  public final function users()
  {
    return $this->belongsToMany(User::class);
  }
}
