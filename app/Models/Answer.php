<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'uploaded_at', 
    ];
     
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];
    
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
