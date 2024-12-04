<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  
  
  protected $fillable = [
        'title',
        'content',
        'image',
        'start_date',
        'end_date',
        'price',
        'previous_price',
        'status',
        'author_id',
        'max_students',
        'delivery_mode',
    ];
     
     protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function assignments()
{
    return $this->hasMany(Assignment::class);
}
public function students()
{
    return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                ->withTimestamps();
}
    // Define the relationship with the User model for the publisher
   
    
}
