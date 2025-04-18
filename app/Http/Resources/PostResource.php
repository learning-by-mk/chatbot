<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $post = parent::toArray($request);
        $shouldShowUser = Str::contains($request->path(), $this->resource->getTable());
        return [
            ...$post,
            'user' => $shouldShowUser ? new UserResource($this->user) : null,
        ];
    }
}
