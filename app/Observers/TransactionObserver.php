<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        Log::info('TransactionObserver created' . $transaction->id);

        // $user = $transaction->user;
        // $user->points -= $transaction->points;
        // $user->save();
        // $user->historyPoints()->create([
        //     'points' => $transaction->points,
        //     'description' => 'Nạp điểm từ gói ' . $transaction->points . ' điểm vào ' . now()->format('d/m/Y H:i:s')
        // ]);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
