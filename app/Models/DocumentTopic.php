<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTopic extends Model
{
    protected $fillable = [
        'document_id',
        'topic_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
