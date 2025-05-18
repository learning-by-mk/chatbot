<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'biography' => $this->biography,
            'education' => $this->education,
            'specialization' => $this->specialization,
            'awards' => $this->awards,
            'total_documents' => $this->total_documents,
            'total_downloads' => $this->total_downloads,
            'total_likes' => $this->total_likes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
