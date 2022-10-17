<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMarket extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id', 'user_id'
    ];

    function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
