<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Material;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id', 'title', 'description', 'main_content', 'order', 'duration', 'image', 'code', 'text',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    function section()
    {
        return $this->belongsTo(Section::class);
    }
}
