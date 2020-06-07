<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class Role extends Model
{
  protected $fillable = [
		'title',
		'color',
    'permission_level'
  ];

  public function users()
  {
    return $this->belongsToMany(User::class);
  }
}
