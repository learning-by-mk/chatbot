<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiVoiceResource extends JsonResource
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
            'audio_path' => $this->audio_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'document' => $this->whenLoaded('document', fn($document) => new DocumentResource($document)),
        ];
    }
}
