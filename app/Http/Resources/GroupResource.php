<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user_id' => $this->user_id,
            'slug' => $this->slug,
            // 'thumbnail_path' => $this->whenLoaded('attachments', function () {
            //     return PostAttachmentResource::collection($this->attachments);
            // }),
            'auto_approval' => $this->auto_approval,
            'about' => $this->about,
        ];
    }
}
