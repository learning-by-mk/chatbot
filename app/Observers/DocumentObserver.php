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

        // Kiểm tra status đơn giản hơn, đảm bảo hoạt động với cả string và enum
        $status = is_object($document->status) ? $document->status->value : $document->status;

        if ($status === 'approved') {
            $document->publish_date = now();
            $document->saveQuietly();
            Log::debug("Observer: Document published", ['id' => $document->id]);
        }
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
