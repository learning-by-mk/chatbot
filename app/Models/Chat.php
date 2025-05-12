<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;
    protected $guarded = ['chatbot_question', 'user'];

    protected $casts = [
        'messages' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chatbotQuestion(): BelongsTo
    {
        return $this->belongsTo(ChatbotQuestion::class);
    }
}
