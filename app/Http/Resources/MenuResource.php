<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'parent' => $this->whenLoaded('parent', fn() => new MenuResource($this->parent)),
            'children' => $this->whenLoaded('children', fn() => MenuResource::collection($this->children), []),
        ];
    }
}
