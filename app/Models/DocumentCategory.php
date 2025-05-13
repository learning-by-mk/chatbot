<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = [
        'document_id',
        'category_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
