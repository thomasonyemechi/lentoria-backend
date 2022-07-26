<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'course_id', 'status', 'role'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
