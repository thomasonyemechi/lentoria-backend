<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInfo extends Model
{
    use HasFactory;

    protected $fillable = ['course_id','what_you_will_learn','course_requirement','course_audience','purpose', 'opportunities'];
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
