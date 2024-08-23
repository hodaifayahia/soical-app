<?php

namespace App\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostAttachementResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->user), // Remove `new` keyword
            'group' => $this->group,
            'attachments' => PostAttachementResource::collection($this->attachments), // Corrected spelling
            'num_of_reaction' => $this->reactions_count,
            'num_of_comment' => $this->comments_count,
            'current_user_has_reaction' => $this->reactions->count() > 0 ,
            'Comments' => CommentResource::collection($this->latest5Comment) ,
        ];
    }
}
