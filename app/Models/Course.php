<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'topic_id', 'user_id', 'title', 'subtitle', 'description', 'language', 'image', 'video', 'level', 'course_type', 'slug', 'price', 'currency'
    ];


    function user(){
        return $this->belongsTo(User::class);
    }
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

    function scopeOfGetUser($query,$type){
        return $query->where('user_id',$type);
    }

    function wishlist(){
        return $this->hasMany(Wishlist::class);
    }

    function category(){
        return $this->belongsTo(Category::class);
    }

    function faqs()
    {
        return $this->hasMany(Faq::class, 'course_id');
    }
}
