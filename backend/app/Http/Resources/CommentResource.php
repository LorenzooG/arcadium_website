<?php

namespace App\Http\Resources;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CommentResource
 *
 * @property Comment resource
 *
 * @package App\Http\Resources
 */
final class CommentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public final function toArray($request)
  {
    return [
      'id' => $this->resource->id,
      'content' => $this->resource->content,
      'created_by' => route('users.show', [
        'user' => $this->resource->user_id
      ]),
      'created_at' => $this->resource->created_at,
      'updated_at' => $this->resource->updated_at
    ];
  }
}
