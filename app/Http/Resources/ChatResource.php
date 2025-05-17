<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'user' => $this->whenLoaded('user', fn($user) => new UserResource($user)),
            'document' => $this->whenLoaded('document', fn($document) => new DocumentResource($document)),
        ];
    }
}
