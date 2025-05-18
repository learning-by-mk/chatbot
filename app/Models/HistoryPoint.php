<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPoint extends Model
{
    protected $guarded = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
