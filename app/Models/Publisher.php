<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'name',
    //     'logo_path',
    //     'address',
    //     'email',
    //     'phone',
    //     'website',
    //     'description'
    // ];
    protected $guarded = [];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function statistics()
    {
        $total_documents = $this->documents()->count();
        $total_downloads = $this->documents()->withCount('downloads')->get()->sum('downloads_count');
        $total_likes = $this->documents()->withCount('likes')->get()->sum('likes_count');

        return [
            'total_documents' => $total_documents,
            'total_downloads' => $total_downloads,
            'total_likes' => $total_likes,
        ];
    }
}
