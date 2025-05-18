<?php

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Log;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void {}

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        Log::debug("Observer: Document updated event fired", ['id' => $document->id, 'status' => $document->status]);

        $status = is_object($document->status) ? $document->status->value : $document->status;

        if ($status === 'approved') {
            $document->publish_date = now();
            $document->saveQuietly();
            Log::debug("Observer: Document published", ['id' => $document->id]);
        }

        $author = $document->author;
        $is_have_history_point = $author->historyPoints()->where('document_id', $document->id)->where('type', 'publish')->exists();
        Log::info('is_have_history_point', ['is_have_history_point' => $is_have_history_point]);
        if ($is_have_history_point) {
            return;
        }
        $author->points += 100;
        $author->save();
        $author->historyPoints()->create([
            'points' => 100,
            'document_id' => $document->id,
            'type' => 'publish',
            'description' => 'Tài liệu ' . $document->title . ' được phê duyệt vào ' . now()->format('d/m/Y H:i:s')
        ]);
    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleted(Document $document): void
    {
        Log::debug("Observer: Document deleted event fired", ['id' => $document->id]);
    }

    /**
     * Handle the Document "restored" event.
     */
    public function restored(Document $document): void
    {
        Log::debug("Observer: Document restored event fired", ['id' => $document->id]);
    }

    /**
     * Handle the Document "force deleted" event.
     */
    public function forceDeleted(Document $document): void
    {
        Log::debug("Observer: Document force deleted event fired", ['id' => $document->id]);
    }
}
