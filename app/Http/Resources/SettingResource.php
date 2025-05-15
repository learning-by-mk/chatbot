<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'setting_group_id' => $this->setting_group_id,
            'group' => $this->whenLoaded('group', fn($group) => new SettingGroupResource($group)),
            'parent' => $this->whenLoaded('parent', fn($parent) => new SettingResource($parent)),
            'children' => $this->whenLoaded('children', fn($children) => SettingResource::collection($children), []),
        ];
    }
}
