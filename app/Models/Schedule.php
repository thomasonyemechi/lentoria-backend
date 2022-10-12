<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable  = [
        'course_id', 'ymd', 'starts', 'ends'
    ];

    function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    


}
