<?php

namespace App\Http\Resources;

use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CommentResource
 *
 * @property User resource
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
    /** @var Comment $comment */
    $comment = $this->resource instanceof Comment
      ? $this->resource
      : $this->resource->pivot;

    return [
      'id' => $comment->id,
      'content' => $comment->content,
      'created_by' => route('users.show', [
        'user' => $comment->user_id
      ]),
      'created_at' => $comment->created_at,
      'updated_at' => $comment->updated_at
    ];
  }
}
