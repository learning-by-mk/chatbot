<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'author_id' => $this->author_id,
            'uploaded_by_id' => $this->uploaded_by_id,
            'category_id' => $this->category_id,
            'publish_date' => $this->publish_date,
            'download_count' => $this->download_count,
            'view_count' => $this->view_count,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'comments' => $this->whenLoaded('comments', fn($comments) => CommentResource::collection($comments), []),
            'ratings' => $this->whenLoaded('ratings', fn($ratings) => RatingResource::collection($ratings), []),
            'downloads' => $this->whenLoaded('downloads', fn($downloads) => DownloadResource::collection($downloads), []),
            'favorites' => $this->whenLoaded('favorites', fn($favorites) => FavoriteResource::collection($favorites), []),
            'ai_summaries' => $this->whenLoaded('aiSummaries', fn($aiSummaries) => AiSummaryResource::collection($aiSummaries), []),
            'ai_voices' => $this->whenLoaded('aiVoices', fn($aiVoices) => AiVoiceResource::collection($aiVoices), []),
            'chatbot_questions' => $this->whenLoaded('chatbotQuestions', fn($chatbotQuestions) => ChatbotQuestionResource::collection($chatbotQuestions), []),
            'categories' => $this->whenLoaded('categories', fn($categories) => CategoryResource::collection($categories), []),
            'author' => $this->whenLoaded('author', fn($author) => new UserResource($author)),
            'uploaded_by' => $this->whenLoaded('uploadedBy', fn($uploadedBy) => new UserResource($uploadedBy)),
        ];
    }
}
