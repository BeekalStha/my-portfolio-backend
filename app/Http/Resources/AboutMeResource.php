<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AboutMeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'title' => $this->title,
            'slug' => $this->slug,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'age' => $this->age,
            'bio' => $this->bio,
            'description' => $this->description,
            'profile_picture' => $this->profile_picture ? Storage::url($this->profile_picture) : null,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'social_links' => $this->social_links,
        ];
    }

    
}


