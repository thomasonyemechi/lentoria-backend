<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'type', 'a', 'b', 'c', 'd', 'type', 'staus'
    ];
}
