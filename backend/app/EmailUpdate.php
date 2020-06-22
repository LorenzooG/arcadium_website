<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UpdateEmailRequest
 * @package App
 *
 * @property int id
 * @property string token
 * @property string origin_address
 * @property int user_id
 * @property User user
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static EmailUpdate create(array $data)
 * @method static EmailUpdate findOrFail(int $id)
 */
final class EmailUpdate extends Model
{
  protected $fillable = [
    'token',
    'user_id',
    'origin_address'
  ];

  protected $hidden = [
    'token'
  ];

  /**
   * Checks if token still valid
   *
   * @return bool
   */
  public final function isValid()
  {
    return $this->created_at->addDays(3)->gte(now());
  }

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
