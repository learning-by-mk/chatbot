<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $guarded = [
        'comments',
        'ratings',
        'downloads',
        'favorites',
        'ai_summaries',
        'ai_voices',
        'chatbot_questions',
        'categories',
        'author',
        'uploaded_by',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
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

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'document_categories');
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
}
