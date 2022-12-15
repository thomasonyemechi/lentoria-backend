<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linkland extends Model
{
    use HasFactory;
    protected $fillable = [
        'ref', 'item', 'system_id', 'media'
    ];


    function item()
    {
        return $this->belongsTo(Course::class, 'item');
    }

    function ref()
    {
        return $this->belongsTo(User::class, 'ref');
    }
}
