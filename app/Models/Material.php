<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lecture;

class Material extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'link', 'type', 'scripts', 'lecture_id'];

    // public function getAttribute($key)
    // {
    //     return str_replace('_','',$key);
    // }

    public function lectures(){
        return $this->belongsTo(Lecture::class);
    }
}