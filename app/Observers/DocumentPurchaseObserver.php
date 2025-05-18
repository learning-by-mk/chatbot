<?php

namespace App\Observers;

use App\Models\DocumentPurchase;
use Illuminate\Support\Facades\Log;

class DocumentPurchaseObserver
{
    /**
     * Handle the DocumentPurchase "created" event.
     */
    public function created(DocumentPurchase $documentPurchase): void
    {
        Log::info('DocumentPurchaseObserver created' . $documentPurchase->id);
        $user = $documentPurchase->user;
        Log::info('points before', ['points' => $user->points]);
        $user->points -= $documentPurchase->document->price->points;
        Log::info('user points', ['user_points' => $user->points, 'document_points' => $documentPurchase->document->price->points]);
        Log::info('points after', ['points' => $user->points]);
        $user->save();
        $user->historyPoints()->create([
            'points' => $documentPurchase->document->price->points,
            'document_id' => $documentPurchase->document_id,
            'type' => 'other',
            'description' => 'Mua tài liệu ' . $documentPurchase->document->title . ' với giá ' . $documentPurchase->document->price->points . ' điểm vào ' . now()->format('d/m/Y H:i:s')
        ]);
    }

    /**
     * Handle the DocumentPurchase "updated" event.
     */
    public function updated(DocumentPurchase $documentPurchase): void
    {
        //
    }

    /**
     * Handle the DocumentPurchase "deleted" event.
     */
    public function deleted(DocumentPurchase $documentPurchase): void
    {
        //
    }

    /**
     * Handle the DocumentPurchase "restored" event.
     */
    public function restored(DocumentPurchase $documentPurchase): void
    {
        //
    }

    /**
     * Handle the DocumentPurchase "force deleted" event.
     */
    public function forceDeleted(DocumentPurchase $documentPurchase): void
    {
        //
    }
}
