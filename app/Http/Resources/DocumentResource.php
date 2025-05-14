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
            'slug' => $this->slug,
            'content' => $this->content,
            'author_id' => $this->author_id,
            'uploaded_by_id' => $this->uploaded_by_id,
            'publish_date' => $this->publish_date,
            'download_count' => $this->downloads()->count(),
            'review_count' => $this->comments()->count(),
            'like_count' => $this->likes()->count(),
            'view_count' => $this->views()->count(),
            'rating' => $this->ratings(),
            'favorite_count' => $this->favorites()->count(),
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'comments' => $this->whenLoaded('comments', fn() => CommentResource::collection($this->comments), []),
            'ratings' => $this->whenLoaded('ratings', fn() => RatingResource::collection($this->ratings), []),
            'downloads' => $this->whenLoaded('downloads', fn() => DownloadResource::collection($this->downloads), []),
            'favorites' => $this->whenLoaded('favorites', fn() => FavoriteResource::collection($this->favorites), []),
            'ai_summaries' => $this->whenLoaded('aiSummaries', fn() => AiSummaryResource::collection($this->aiSummaries), []),
            'ai_voices' => $this->whenLoaded('aiVoices', fn() => AiVoiceResource::collection($this->aiVoices), []),
            'chatbot_questions' => $this->whenLoaded('chatbotQuestions', fn() => ChatbotQuestionResource::collection($this->chatbotQuestions), []),
            'categories' => $this->whenLoaded('categories', fn() => CategoryResource::collection($this->categories), []),
            'author' => $this->whenLoaded('author', fn() => new UserResource($this->author)),
            'uploaded_by' => $this->whenLoaded('uploadedBy', fn() => new UserResource($this->uploadedBy)),
            'file' => $this->whenLoaded('file', fn() => [new FileResource($this->file)]),
            'file_id' => $this->file_id,
            'image' => $this->whenLoaded('image', fn() => [new FileResource($this->image)]),
            'image_id' => $this->image_id,
        ];
    }
}
