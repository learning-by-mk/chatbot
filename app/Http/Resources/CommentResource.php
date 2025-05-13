<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'document' => $this->whenLoaded('document', fn($document) => new DocumentResource($document)),
            'user' => $this->whenLoaded('user', fn($user) => new UserResource($user)),
            'parent' => $this->whenLoaded('parent', fn($parent) => new CommentResource($parent)),
            'children' => $this->whenLoaded('children', fn($children) => CommentResource::collection($children), []),
        ];
    }
}
