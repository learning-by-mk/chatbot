<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
        'setting_group_id',
    ];

    public function group()
    {
        return $this->belongsTo(SettingGroup::class);
    }
}
