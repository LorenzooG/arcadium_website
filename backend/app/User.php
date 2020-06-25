<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\Tokens\JwtRepository;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * @package App
 *
 * @property int id
 * @property string name
 * @property string user_name
 * @property string email
 * @property string avatar_url
 * @property string password
 * @property Collection<Role> roles
 * @property Collection<Post> posts
 * @property Carbon email_verified_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property mixed pivot
 *
 * @method static User create(array $array)
 * @method static User findOrFail(int $int)
 */
final class User extends Authenticatable
{

  /**
   * The storage key that the system will use to save the avatars
   */
  public const AVATARS_STORAGE_KEY = 'users.avatars';

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
    'avatar_url',
    'email_verified_at'
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
    return ($this->permissions() & $permission) !== 0
      && $this->hasVerifiedEmail();
  }

  /**
   * Retrieve user's all permissions
   *
   * @return int
   */
  public final function permissions(): int
  {
    if (!$this->exists) return config('app.default_permissions', Permission::NONE);

    return $this->roles
      ->map(fn($role) => $role->permission_level)
      ->reduce(fn($role, $otherRole) => $role | $otherRole, Permission::NONE);
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
   * Sets password attribute
   *
   * @param string $value
   * @noinspection PhpUnused
   */
  public final function setPasswordAttribute(string $value)
  {
    $this->attributes["password"] = Hash::make(htmlspecialchars_decode($value));

    if (!$this->exists) return;

    /** @var TokenRepositoryInterface $jwtRepository */
    $jwtRepository = app(JwtRepository::class);
    $jwtRepository->delete($this);
  }

  /**
   * Send the email verification notification.
   *
   * @return void
   */
  public final function sendEmailVerificationNotification()
  {
    $this->notify(new VerifyEmailNotification());
  }
}
