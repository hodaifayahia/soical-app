<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 
            "id"=> $this->id,
            "name"=> $this->name,
            "email"=> $this->email,
            "role" =>$this->role,
            "status" =>$this->status,
            "group_id" =>$this->group_id,
            "username"=> $this->username,
            "cover_url"=> $this->cover_path ? asset(Storage::url($this->cover_path))  : 'public/img/default_cover.jpg',
            "avatar_url"=>$this->avatar_path ? asset(Storage::url($this->avatar_path))  : 'public/img/default_avatar.jpg',
        ];
    }
}
