<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLike extends Model
{
    // protected $fillable = [
    //     'document_id',
    //     'user_id'
    // ];
    protected $guarded = ['user', 'document'];
}
