<?php

namespace App\Models;

use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;
    protected $guarded = [
        'comments',
        'ratings',
        'downloads',
        'favorites',
        'ai_summary',
        'ai_voice',
        'chatbot_questions',
        'category',
        'topics',
        'author',
        'uploaded_by',
        'file',
        'likes',
        'publisher',
        'likes',
        'price',
        'purchases',
        'is_free'
    ];

    protected function casts()
    {
        return [
            'average_rating' => 'decimal:1',
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // public function ratings()
    // {
    //     $comments = $this->comments()->whereNull('parent_id')->get();
    //     $ratings = 0;
    //     foreach ($comments as $comment) {
    //         $ratings += $comment->score;
    //     }
    //     return round($ratings / ($comments->count() || 1), 1);
    // }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function ai_summary(): HasOne
    {
        return $this->hasOne(AiSummary::class);
    }

    public function ai_voice(): HasOne
    {
        return $this->hasOne(AiVoice::class);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class)->where('user_id', Auth::id())->first();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function uploaded_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(DocumentLike::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'document_topics');
    }
    public function price(): HasOne
    {
        return $this->hasOne(DocumentPrice::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(DocumentPurchase::class);
    }

    public function isPurchased(User $user): bool
    {
        return $this->purchases()->where('user_id', $user->id)->exists();
    }

    public function isFree(): bool
    {
        /** @var DocumentPrice $price */
        $price = $this->price;
        return $price && $price->is_free;
    }
}
