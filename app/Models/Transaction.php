<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'user_id', 'course_owner_id', 'course_id', 'amount', 'status'];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function owner()
    {
        return $this->belongsTo(User::class, 'course_owner_id');
    }

    function course()
    {
        return $this->belongsTo(Course::class);
    }
}