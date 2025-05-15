<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'content' => $this->content,
            'status' => $this->status,
            'admin_response' => $this->admin_response,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'responded_at' => $this->responded_at,
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'responded_by' => $this->whenLoaded('respondedBy', fn() => new UserResource($this->respondedBy)),
        ];
    }
}
