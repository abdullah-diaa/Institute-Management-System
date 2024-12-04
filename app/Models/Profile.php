<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['location', 'phone_number', 'date_of_birth', 'profile_picture', 'user_id','last_phone_update'];


    protected $casts = [

        'last_phone_update'=> 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
