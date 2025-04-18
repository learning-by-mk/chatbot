<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = parent::toArray($request);
        $shouldShowPosts = Str::contains($request->path(), $this->resource->getTable());
        return [
            ...$user,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'posts' => $shouldShowPosts ? PostResource::collection($this->posts) : null,
        ];
    }
}
