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
            'parent_id' => $this->parent_id,
            'comment' => $this->comment,
            'score' => $this->score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'like_count' => $this->likes()->count(),
            'document' => $this->whenLoaded('document', fn() => new DocumentResource($this->document)),
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'parent' => $this->whenLoaded('parent', fn() => new CommentResource($this->parent)),
            'children' => $this->whenLoaded('children', fn() => CommentResource::collection($this->children), []),
        ];
    }
}
