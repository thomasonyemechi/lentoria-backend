<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'description'
    ];

    function courses()
    {
        return $this->hasMany(Course::class, 'course_type');
    }
}
