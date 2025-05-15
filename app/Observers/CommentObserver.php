<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        $document = $comment->document;
        $document->average_rating = $document->ratings();
        $document->save();
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
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
