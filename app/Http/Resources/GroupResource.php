<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
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
            'status' => $this->currectUserGroup?->status,
            'role' => $this->currectUserGroup?->role,
            'thumbnail_url' =>$this->thumbnail_path ? Storage::url($this->thumbnail_path) : '/img/no-image.png',
            'cover_url' =>$this->cover_path ? Storage::url($this->cover_path) : null,
            'description' => Str::words(strip_tags($this->about , 20)),
            'auto_approval' => $this->auto_approval,
            'about' => $this->about,
        ];
    }
}
