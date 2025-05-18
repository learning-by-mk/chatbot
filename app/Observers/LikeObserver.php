<?php

namespace App\Observers;

use App\Models\DocumentLike;
use Illuminate\Support\Facades\Log;

class LikeObserver
{
    /**
     * Handle the Like "created" event.
     */
    public function created(DocumentLike $like): void
    {
        Log::info('LikeObserver created', ['like' => $like]);
        $author = $like->document->author;
        $profile = $author->author_profile;
        $profile->total_likes += 1;
        $profile->save();
    }

    /**
     * Handle the Like "updated" event.
     */
    public function updated(DocumentLike $like): void
    {
        //
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(DocumentLike $like): void
    {
        //
    }

    /**
     * Handle the Like "restored" event.
     */
    public function restored(DocumentLike $like): void
    {
        //
    }

    /**
     * Handle the Like "force deleted" event.
     */
    public function forceDeleted(DocumentLike $like): void
    {
        //
    }
}
