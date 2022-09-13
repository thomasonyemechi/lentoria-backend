<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Material;

class Lecture extends Model
{
    use HasFactory;

    public function materials(){
        return $this->hasMany(Material::class);
    }

    protected $fillable = [
        'section_id', 'title', 'description', 'main_content', 'order', 'duration', 'image', 'code', 'text'
    ];
}