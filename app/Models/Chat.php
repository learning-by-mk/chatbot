<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    // protected $fillable = [
    //     'user_id',
    //     'title',
    //     'last_message',
    //     'uuid',
    //     'messages',
    //     'chatbot_question_id',
    // ];
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
