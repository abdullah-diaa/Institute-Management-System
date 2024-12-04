<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'phone',
        'location',
        'request_status',
        'payment_method',
        'details',
        'approved_by',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedByUser()
{
    return $this->belongsTo(User::class, 'approved_by');
}

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
