<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Document;
use App\Models\DocumentPurchase;
use App\Observers\CommentObserver;
use App\Observers\DocumentObserver;
use App\Observers\DownloadObserver;
use App\Models\Download;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Observers\DocumentPurchaseObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Đăng ký observers
        Document::observe(DocumentObserver::class);
        Comment::observe(CommentObserver::class);
        Download::observe(DownloadObserver::class);
        Transaction::observe(TransactionObserver::class);
        DocumentPurchase::observe(DocumentPurchaseObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
