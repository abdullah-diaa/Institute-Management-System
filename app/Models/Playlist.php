<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    
    protected $fillable = ['name', 'description','created_by', 'image'];

    /**
     * Relationship with YoutubeVideo
     * A playlist can have many YouTube videos.
     
     * each playlist has it videos 
     
     */
    public function videos()
    {
        return $this->belongsToMany(YoutubeVideo::class, 'playlist_video', 'playlist_id', 'video_id');
    }
    
public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by'); // Assuming 'created_by' is the foreign key
}

    
}
