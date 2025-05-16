<?php

namespace App\Models;

use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Log;
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
        'ai_summaries',
        'ai_voices',
        'chatbot_questions',
        'category',
        'topics',
        'author',
        'uploaded_by',
        'file',
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

    public function ratings()
    {
        $comments = $this->comments()->whereNull('parent_id')->get();
        $ratings = 0;
        foreach ($comments as $comment) {
            $ratings += $comment->score;
        }
        return round($ratings / ($comments->count() || 1), 1);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function aiSummaries(): HasMany
    {
        return $this->hasMany(AiSummary::class);
    }

    public function aiVoices(): HasMany
    {
        return $this->hasMany(AiVoice::class);
    }

    public function chatbotQuestions(): HasMany
    {
        return $this->hasMany(ChatbotQuestion::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
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


    public function likes(): HasMany
    {
        return $this->hasMany(DocumentLike::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'document_topics');
    }
}
