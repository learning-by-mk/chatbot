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
            'rating' => $this->average_rating,
            'average_rating' => $this->average_rating,
            'favorite_count' => $this->favorites()->count(),
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'comments' => $this->whenLoaded('comments', fn() => CommentResource::collection($this->comments), []),
            'ratings' => $this->whenLoaded('ratings', fn() => RatingResource::collection($this->ratings), []),
            'downloads' => $this->whenLoaded('downloads', fn() => DownloadResource::collection($this->downloads), []),
            'favorites' => $this->whenLoaded('favorites', fn() => FavoriteResource::collection($this->favorites), []),
            'ai_summary' => $this->whenLoaded('ai_summary', fn() => new AiSummaryResource($this->ai_summary)),
            'ai_voice' => $this->whenLoaded('ai_voice', fn() => new AiVoiceResource($this->ai_voice)),
            'chat' => $this->whenLoaded('chat', fn() => ChatResource::collection($this->chat)),
            'category' => $this->whenLoaded('category', fn() => new CategoryResource($this->category)),
            'topics' => $this->whenLoaded('topics', fn() => TopicResource::collection($this->topics), []),
            'author' => $this->whenLoaded('author', fn() => new UserResource($this->author)),
            'uploaded_by' => $this->whenLoaded('uploaded_by', fn() => new UserResource($this->uploaded_by)),
            'file' => $this->whenLoaded('file', fn() => [new FileResource($this->file)]),
            'file_id' => $this->file_id,
            'image' => $this->whenLoaded('image', fn() => [new FileResource($this->image)]),
            'image_id' => $this->image_id,
        ];
    }
}
