<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['user', 'point_package'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentPurchases()
    {
        return $this->hasMany(DocumentPurchase::class);
    }

    public function point_package()
    {
        return $this->belongsTo(PointPackage::class);
    }
}
