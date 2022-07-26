<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'biography', 'status', 'headline','biography','language','website_url','twitter','facebook','linkedin','youtube'
    ];


    function user()
    {
        return $this->belongsTo(User::class);
    }
}
