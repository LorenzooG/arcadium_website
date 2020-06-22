<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property Carbon deleted_at
 *
 * @method static User create(array $array)
 * @method static User findOrFail(int $int)
 */
final class User extends Authenticatable
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

  /**
   * Retrieve the payments that this user have
   *
   * @return HasMany
   */
  public final function payments()
  {
    return $this->hasMany(Payment::class);
  }

  /**
   * Retrieve the roles that this user have
   *
   * @return BelongsToMany
   */
  public final function roles()
  {
    return $this->belongsToMany(Role::class);
  }

  /**
   * Retrieve the comments that this user made
   *
   * @return HasMany
   */
  public final function comments()
  {
    return $this->hasMany(Comment::class);
  }

  /**
   * Retrieve the posts that this user post
   *
   * @return HasMany
   */
  public final function posts()
  {
    return $this->hasMany(Post::class);
  }

  /**
   * Checks user permission
   *
   * @param int $permission
   * @return bool
   */
  public final function hasPermission(int $permission)
  {
    return ($this->permissions() & $permission) !== 0;
  }

  /**
   * Retrieve user's all permissions
   *
   * @return int
   */
  public final function permissions(): int
  {
    return $this->roles
      ->map(fn($role) => $role->permission_level)
      ->reduce(fn($role, $otherRole) => $role | $otherRole, 1);
  }

  /**
   * Retrieve the email updates that this user have
   *
   * @return HasMany
   */
  public final function emailUpdates()
  {
    return $this->hasMany(EmailUpdate::class);
  }

  /**
   * Send the password reset notification
   *
   * @param string $token
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new PasswordResetNotification($token));
  }

  /**
   * Sets username attribute
   *
   * @param string $value
   * @throws ConflictHttpException
   */
  public final function setUserNameAttribute(string $value)
  {
    $this->attributes["user_name"] = $value;
  }

  /**
   * Sets password attribute
   *
   * @param string $value
   */
  public final function setPasswordAttribute(string $value)
  {
    $this->attributes["password"] = Hash::make(htmlspecialchars_decode($value));
  }
}
