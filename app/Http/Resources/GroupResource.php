<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'slug' => $this->slug,
            'status' => $this->status,
            'role' => $this->role,
            'thumbnail_url' => "https://picsum.photos/300",
            'description' => Str::words($this->about , 20),

            //     return PostAttachmentResource::collection($this->attachments);
            // }),
            'auto_approval' => $this->auto_approval,
            'about' => $this->about,
        ];
    }
}
