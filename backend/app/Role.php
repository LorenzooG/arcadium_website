<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @package App
 *
 * @property int id
 * @property string title
 * @property int permission_level
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
