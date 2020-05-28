<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @package App
 *
 * @property int id
 * @property string name
 * @property string user_name
 * @property string email
 * @property string password
 * @property Collection<Role> roles
 * @property Collection<Post> posts
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static User create(array $array)
 * @method static User findOrFail(int $int)
 */
class User extends Authenticatable
{
  use Notifiable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'user_name',
    'avatar_url'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'avatar_url',
    'remember_token',
    'email'
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'is_admin' => 'boolean'
  ];

  public function payments()
  {
    return $this->hasMany(Payment::class);
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }

  public function posts()
  {
    return $this->hasMany(Post::class);
  }

  public function hasPermission(int $permission)
  {
    return ($this->permissions() & $permission) !== 0;
  }

  public function permissions(): int
  {
    return $this->roles
      ->map(fn($role) => $role->permission_level)
      ->reduce(fn($role, $otherRole) => $role | $otherRole, 1);
  }

  public function emailUpdates()
  {
    return $this->hasMany(EmailUpdate::class);
  }

  /**
   * @param string $value
   * @throws ConflictHttpException
   */
  public function setUserNameAttribute(string $value)
  {
    $this->attributes["user_name"] = $value;
  }

  public function setPasswordAttribute(string $value)
  {
    $this->attributes["password"] = Hash::make($value);
  }
}
