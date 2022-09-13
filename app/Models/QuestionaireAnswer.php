<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionaireAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'question_id', 'data', 'total_questions'
    ];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
