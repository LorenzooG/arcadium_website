<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Punishment
 *
 * @property int id
 * @property string punished_user_name
 * @property string reason
 * @property string proof
 * @property string punished_by
 * @property float punished_at
 * @property float punished_duration
 * @property float punished_until
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static Punishment findOrFail($id)
 * @method static Punishment create($data)
 *
 * @package App
 */
final class Punishment extends Model
{
  /**
   * The model's attributes.
   *
   * @var array
   */
  protected $attributes = [
    'punished_at' => 0,
    'punished_until' => 0
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'punished_duration',
    'punished_user_name',
    'reason',
    'proof',
    'punished_until',
    'punished_at',
    'punished_by',
  ];

  /**
   * Sets punished_until attribute
   *
   * @param $value
   * @noinspection PhpUnused
   */
  public final function setPunishedUntilAttribute($value)
  {
    $this->attributes['punished_until'] = $value;

    $this->attributes['punished_duration'] = Carbon::createFromTimestampMs($this->attributes['punished_at'])
      ->diffInMilliseconds(Carbon::createFromTimestampMs($this->attributes['punished_until']));
  }

  /**
   * Sets punished_at attribute
   *
   * @param $value
   * @noinspection PhpUnused
   */
  public final function setPunishedAtAttribute($value)
  {
    $this->attributes['punished_at'] = $value;

    $this->attributes['punished_duration'] = Carbon::createFromTimestampMs($this->attributes['punished_at'])
      ->diffInMilliseconds(Carbon::createFromTimestampMs($this->attributes['punished_until']));
  }
}
