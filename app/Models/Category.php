<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function topics()
    {
        return $this->hasMany(Topic::class, 'category_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    function published_courses()
    {
        return $this->hasManyThrough(Course::class, Topic::class)->inRandomOrder()->where('published','=', 1)->take(25);
    }
}