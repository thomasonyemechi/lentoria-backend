<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'topic_id', 'user_id', 'title', 'subtitle', 'description', 'language', 'image', 'video', 'level', 'course_type', 'slug'
    ];


    function owners()
    {
        return $this->hasMany(CourseOwner::class);
    }

    function info()
    {
        return $this->hasOne(CourseInfo::class);
    }

    function admin()
    {
        return $this->belongsTo(User::class);
    }

    function scopeUser($query){
        return $query->where('user_id',auth()->user()->id);
    }

    function wishlist(){
        return $this->hasMany(Wishlist::class);
    }
}
