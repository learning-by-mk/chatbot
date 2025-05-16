<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Document;
use App\Models\DocumentLike;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function get_interaction_data()
    {

        return response()->json([
            'ratings' => $this->get_ratings(),
            'likes' => $this->get_likes(),
            'comments' => $this->get_comments(),
            'downloads' => $this->get_downloads(),
        ]);
    }

    public function get_interaction_statistics()
    {
        return response()->json([
            'data' => [
                'summary' => $this->get_interaction_summary(),
                'timeSeriesData' => $this->get_time_series_data(),
                'topDocuments' => $this->get_top_documents(),
                'interactionDistribution' => [
                    'labels' => ['Thích', 'Bình luận', 'Tải xuống', 'Đánh giá'],
                    'values' => [$this->get_likes(), $this->get_comments(), $this->get_downloads(), $this->get_ratings()],
                ],
            ]
        ]);
    }

    public function get_interaction_summary()
    {
        // Tu00ednh su1ed1 tu00e0i liu1ec7u
        $documentCount = Document::count() ?: 1; // Tru00e1nh chia cho 0

        // Lu1ea5y thu1eddi gian 30 ngu00e0y tru01b0u1edbc
        $oneMonthAgo = now()->subDays(30);

        // Tu1ed5ng hu1ee3p du1eef liu1ec7u ratings (comment cu00f3 score)
        $allRatings = Comment::whereNull('parent_id')->where('score', '>', 0)->get();
        $newRatings = Comment::whereNull('parent_id')->where('score', '>', 0)
            ->where('created_at', '>=', $oneMonthAgo)->count();
        $ratingTotal = $allRatings->count();
        $ratingAverage = $ratingTotal > 0 ? round($allRatings->avg('score'), 1) : 0;
        $lastMonthRatingCount = Comment::whereNull('parent_id')->where('score', '>', 0)
            ->whereBetween('created_at', [now()->subDays(60), $oneMonthAgo])->count() ?: 1;
        $ratingGrowthRate = round(($newRatings / $lastMonthRatingCount) * 100);

        // Tu1ed5ng hu1ee3p du1eef liu1ec7u favorites (likes)
        $favoriteTotal = DocumentLike::count();
        $newFavorites = DocumentLike::where('created_at', '>=', $oneMonthAgo)->count();
        $favoritePerDocument = round($favoriteTotal / $documentCount, 1);
        $lastMonthFavoriteCount = DocumentLike::whereBetween('created_at', [now()->subDays(60), $oneMonthAgo])->count() ?: 1;
        $favoriteGrowthRate = round(($newFavorites / $lastMonthFavoriteCount) * 100);

        // Tu1ed5ng hu1ee3p du1eef liu1ec7u comments
        $commentTotal = Comment::count();
        $newComments = Comment::where('created_at', '>=', $oneMonthAgo)->count();
        $commentPerDocument = round($commentTotal / $documentCount, 1);
        $lastMonthCommentCount = Comment::whereBetween('created_at', [now()->subDays(60), $oneMonthAgo])->count() ?: 1;
        $commentGrowthRate = round(($newComments / $lastMonthCommentCount) * 100);

        // Tu1ed5ng hu1ee3p du1eef liu1ec7u downloads
        $downloadTotal = Download::count();
        $newDownloads = Download::where('created_at', '>=', $oneMonthAgo)->count();
        $downloadPerDocument = round($downloadTotal / $documentCount, 1);
        $lastMonthDownloadCount = Download::whereBetween('created_at', [now()->subDays(60), $oneMonthAgo])->count() ?: 1;
        $downloadGrowthRate = round(($newDownloads / $lastMonthDownloadCount) * 100);

        return [
            'ratings' => [
                'total' => $ratingTotal,
                'average' => $ratingAverage,
                'newCount' => $newRatings,
                'growthRate' => $ratingGrowthRate,
            ],
            'favorites' => [
                'total' => $favoriteTotal,
                'newCount' => $newFavorites,
                'averagePerDocument' => $favoritePerDocument,
                'growthRate' => $favoriteGrowthRate,
            ],
            'comments' => [
                'total' => $commentTotal,
                'newCount' => $newComments,
                'averagePerDocument' => $commentPerDocument,
                'growthRate' => $commentGrowthRate,
            ],
            'downloads' => [
                'total' => $downloadTotal,
                'newCount' => $newDownloads,
                'averagePerDocument' => $downloadPerDocument,
                'growthRate' => $downloadGrowthRate,
            ],
        ];
    }

    public function get_time_series_data()
    {
        // Lấy các tháng để làm labels
        $months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'];

        // Lấy dữ liệu ratings từ comments
        $ratings = Comment::whereNull('parent_id')
            ->whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('m');
            });

        // Lấy dữ liệu favorites từ DocumentLike
        $favorites = DocumentLike::whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('m');
            });

        // Lấy dữ liệu comments
        $comments = Comment::whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('m');
            });

        // Lấy dữ liệu downloads
        $downloads = Download::whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('m');
            });

        // Chuẩn bị dữ liệu theo định dạng mong muốn
        $ratingsCount = [];
        $ratingsAverage = [];
        $favoritesCount = [];
        $commentsCount = [];
        $downloadsCount = [];

        // Khởi tạo mảng với giá trị mặc định là 0 cho mỗi tháng
        for ($i = 1; $i <= 6; $i++) {
            $month = $i;
            $ratingsCount[$i - 1] = $ratings->has($month) ? $ratings[$month]->count() : 0;
            $ratingsAverage[$i - 1] = $ratings->has($month) ? round($ratings[$month]->avg('score'), 1) : 0;
            $favoritesCount[$i - 1] = $favorites->has($month) ? $favorites[$month]->count() : 0;
            $commentsCount[$i - 1] = $comments->has($month) ? $comments[$month]->count() : 0;
            $downloadsCount[$i - 1] = $downloads->has($month) ? $downloads[$month]->count() : 0;
        }

        return [
            'ratings' => [
                'counts' => [
                    'labels' => $months,
                    'values' => $ratingsCount,
                ],
                'averageScores' => [
                    'labels' => $months,
                    'values' => $ratingsAverage,
                ],
            ],
            'favorites' => [
                'counts' => [
                    'labels' => $months,
                    'values' => $favoritesCount,
                ],
            ],
            'comments' => [
                'counts' => [
                    'labels' => $months,
                    'values' => $commentsCount,
                ],
            ],
            'downloads' => [
                'counts' => [
                    'labels' => $months,
                    'values' => $downloadsCount,
                ],
            ],
        ];
    }

    public function get_top_documents()
    {
        $documents = Document::where('average_rating', '>', 0)
            ->orderBy('average_rating', 'desc')
            ->take(5)
            ->get()
            ->map(function ($document) {
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'interactionCount' => $document->comments->count() + $document->likes->count() + $document->downloads->count(),
                    'interactionTypes' => [
                        'ratings' => $document->average_rating,
                        'favorites' => $document->likes->count(),
                        'comments' => $document->comments->count(),
                        'downloads' => $document->downloads->count(),
                    ]
                ];
            });

        return $documents;
    }

    public function get_likes()
    {
        $likes = DocumentLike::all()->count();
        return $likes;
    }

    public function get_comments()
    {
        $comments = Comment::all()->count();
        return $comments;
    }

    public function get_downloads()
    {
        $downloads = Download::all()->count();
        return $downloads;
    }

    public function get_ratings()
    {
        $ratings = Comment::all()->reduce(function ($carry, $item) {
            $carry += $item->score;
            return $carry;
        }, 0) / (Comment::count() || 1);
        return $ratings;
    }
}
