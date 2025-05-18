<?php

namespace App\Observers;

use App\Models\Download;
use Illuminate\Support\Facades\Log;

class DownloadObserver
{
    /**
     * Handle the Download "created" event.
     */
    public function created(Download $download): void
    {
        Log::info('DownloadObserver created');
        try {
            $document = $download->document;
            $author = $document->author;
            $profile = $author->author_profile;
            $profile->total_downloads += 1;
            $profile->save();

            $author = $download->document->author;
            $is_have_history_point = $author->historyPoints()->where('document_id', $download->document_id)->where('type', 'download')->exists();
            Log::info('is_have_history_point', ['is_have_history_point' => $is_have_history_point]);
            if ($is_have_history_point) {
                return;
            }
            $author->points += $download->document->price->points;
            $author->save();
            // $history_point = $author->historyPoints()->where('document_id', $download->document_id)->first();

            $author->historyPoints()->create([
                'points' => $download->document->price->points,
                'document_id' => $download->document_id,
                'type' => 'download',
                'description' => 'Tài liệu ' . $download->document->title . ' với giá ' . $download->document->price->points . ' điểm được tải xuống vào ' . now()->format('d/m/Y H:i:s')
            ]);
        } catch (\Exception $e) {
            Log::error('DownloadObserver created error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle the Download "updated" event.
     */
    public function updated(Download $download): void
    {
        //
    }

    /**
     * Handle the Download "deleted" event.
     */
    public function deleted(Download $download): void
    {
        //
    }

    /**
     * Handle the Download "restored" event.
     */
    public function restored(Download $download): void
    {
        //
    }

    /**
     * Handle the Download "force deleted" event.
     */
    public function forceDeleted(Download $download): void
    {
        //
    }
}
