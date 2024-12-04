<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeVideo extends Model
{
    use HasFactory;

  
    protected $fillable = ['title', 'youtube_url', 'description'];

    /**
     *  Dear viewer
     * this is  the
     * Relationship with Playlist
     * A video can belong to many playlists.
     * that makes the playlists very flexible n
     */
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_video', 'video_id', 'playlist_id');
    }
    
}

