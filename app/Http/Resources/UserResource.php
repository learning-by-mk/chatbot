<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Models\Download;
use App\Models\Comment;

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
        $avatar = $this->whenLoaded('avatar', fn() => new FileResource($this->avatar));
        $avatar = $avatar ? [$avatar] : null;

        // Lấy tổng số lượt tải xuống cho tất cả tài liệu của người dùng
        $documentIds = $this->documents()->pluck('id')->toArray();
        $totalDownloads = empty($documentIds) ? 0 : Download::whereIn('document_id', $documentIds)->count();

        // Tính điểm đánh giá trung bình cho tài liệu của người dùng
        $averageRating = 0;
        if (!empty($documentIds)) {
            $ratings = Comment::whereIn('document_id', $documentIds)
                ->whereNull('parent_id')
                ->where('score', '>', 0)
                ->avg('score');
            $averageRating = $ratings ? round($ratings, 1) : 0;
        }

        return [
            ...$user,
            'isAdmin' => $this->isAdmin(),
            'avatar' => $avatar,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'posts' => $this->whenLoaded("posts", fn() => PostResource::collection($this->posts), []),
            'document_count' => $this->documents()->count(),
            'total_downloads' => $totalDownloads,
            'average_rating' => $averageRating,
            'author_profile' => $this->whenLoaded('author_profile', fn() => new AuthorProfileResource($this->author_profile)),
        ];
    }
}
