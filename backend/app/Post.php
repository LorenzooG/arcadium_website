<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 *
 * @property int id
 * @property string name
 * @property string description
 *
 * @method static Post create(array $array)
 * @method static Post findOrFail(int $int)
 */
class Post extends Model
{
  protected $fillable = [
    "name",
    "description"
  ];
}
