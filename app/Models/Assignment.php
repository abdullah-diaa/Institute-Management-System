<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'due_date',
        'file',
    ];

    /**
     * Get the user that created the assignment.
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];
    
     public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course associated with the assignment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
}
