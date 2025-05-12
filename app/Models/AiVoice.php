<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiVoice extends Model
{
    use HasFactory;
    protected $guarded = ['document'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
