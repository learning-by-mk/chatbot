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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'last_message' => $this->last_message,
            'uuid' => $this->uuid,
            'messages' => $this->messages,
            'chatbot_question_id' => $this->chatbot_question_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user', fn($user) => new UserResource($user)),
            'chatbot_question' => $this->whenLoaded('chatbotQuestion', fn($chatbotQuestion) => new ChatbotQuestionResource($chatbotQuestion)),
        ];
    }
}
