<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_agent', 'browser_name', 'version', 'platform', 'pattern', 'system_id', 'logged_in'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
