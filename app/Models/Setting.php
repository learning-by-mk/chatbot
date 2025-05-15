<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    // protected $fillable = [
    //     'key',
    //     'value',
    //     'description',
    //     'setting_group_id',
    //     'parent_id',
    // ];

    protected $guarded = ['group', 'parent', 'children'];

    public function parent()
    {
        return $this->belongsTo(Setting::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Setting::class, 'parent_id');
    }

    public function group()
    {
        return $this->belongsTo(SettingGroup::class, 'setting_group_id');
    }

    public static function getSettingValue($key, $default = null)
    {
        return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}
