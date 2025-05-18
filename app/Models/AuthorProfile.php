<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorProfile extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'biography',
    //     'education',
    //     'specialization',
    //     'awards',
    //     'total_documents',
    //     'total_downloads',
    //     'total_likes'
    // ];

    protected $guarded = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatistics()
    {
        $user = $this->user;
        $this->total_documents = Document::where('author_id', $user->id)->count();
        $this->total_downloads = Document::where('author_id', $user->id)->withCount('downloads')->get()->sum('downloads_count');
        $this->total_likes = Document::where('author_id', $user->id)->withCount('likes')->get()->sum('likes_count');
        $this->save();
    }
}
