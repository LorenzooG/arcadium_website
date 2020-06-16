<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
final class EmailUpdate extends Model
{
  protected $fillable = [
    'token',
    'already_used',
    'origin_address'
  ];

  protected $hidden = [
    'token'
  ];

  /**
   * Retrieve the user owner of this email update
   *
   * @return BelongsTo
   */
  public final function user()
  {
    return $this->belongsTo(User::class);
  }
}
