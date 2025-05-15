<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $guarded = ['documents'];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'document_topics');
    }
}
