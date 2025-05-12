<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
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
            'document_id' => $this->document_id,
            'user_id' => $this->user_id,
            'ip_address' => $this->ip_address,
            'downloaded_at' => $this->downloaded_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'document' => $this->whenLoaded('document', fn($document) => new DocumentResource($document)),
            'user' => $this->whenLoaded('user', fn($user) => new UserResource($user)),
        ];
    }
}
