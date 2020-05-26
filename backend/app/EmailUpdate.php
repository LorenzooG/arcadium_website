<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UpdateEmailRequest
 * @package App
 *
 * @property int id
 * @property string token
 * @property string origin_address
 * @property bool already_used
 * @property User user
 *
 * @method static EmailUpdate create(array $data)
 * @method static EmailUpdate findOrFail(int $id)
 */
class EmailUpdate extends Model
{
  protected $fillable = [
    'token',
    'already_used',
    'origin_address'
  ];

  protected $hidden = [
    'token'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
