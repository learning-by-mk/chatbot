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
        return [
            ...$user,
            'isAdmin' => $this->isAdmin(),
            'avatar' => [$this->whenLoaded('avatar', fn() => new FileResource($this->avatar))],
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'posts' => $this->whenLoaded("posts", fn() => PostResource::collection($this->posts), [])
        ];
    }
}
