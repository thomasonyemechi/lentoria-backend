<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Lecture;

class Section extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function lectures(){
        return $this->hasMany(Lecture::class);
    }
}
