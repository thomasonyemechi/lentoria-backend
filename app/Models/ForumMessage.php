<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'course_id', 'message', 'reply',
    ];

    function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function reply()
    {
        return $this->belongsTo(ForumMessage::class, 'reply');
    }
}
