<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Document;
use App\Models\Download;
use App\Models\DocumentPurchase;
use App\Models\Transaction;
use App\Observers\CommentObserver;
use App\Observers\DocumentObserver;
use App\Observers\DownloadObserver;
use App\Observers\DocumentPurchaseObserver;
use App\Observers\TransactionObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Document::observe(DocumentObserver::class);
        // Comment::observe(CommentObserver::class);
        // Download::observe(DownloadObserver::class);
        // Transaction::observe(TransactionObserver::class);
        // DocumentPurchase::observe(DocumentPurchaseObserver::class);
    }
}
