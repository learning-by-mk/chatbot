<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'category',
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
}
