<?php

namespace App\Http\Resources;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

/**
 * Class PostResource
 * @package App\Http\Resources
 *
 * @property int id
 * @property string title
 * @property string description
 * @property Collection<User> likes
 * @property User user
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method BelongsToMany likes()
 */
final class PostResource extends JsonResource
{
  public bool $isAlone = false;

  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public final function toArray($request)
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->isAlone
        ? $this->description
        : str_replace('', Str::substr($this->description, 0, 1000), $this->description),
      'likes' => $this->likes()->count(),
      'created_by' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'user_name' => $this->user->user_name,
        'avatar' => route('users.avatar', [
          'user' => $this->user->id
        ]),
      ],
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
  }
}
