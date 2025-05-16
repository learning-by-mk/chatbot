<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        Log::info('Comment created', ['comment' => $comment]);
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        Log::info('Comment updated', ['comment' => $comment]);
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        Log::info('Comment deleted', ['comment' => $comment]);
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }
}
