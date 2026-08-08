<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSessionQuestion extends Model
{
    protected $fillable = [

        'quiz_session_id',

        'question_id',

        'position'
    ];

    public function session()
    {
        return $this->belongsTo(
            QuizSession::class,
            'quiz_session_id'
        );
    }

    public function question()
    {
        return $this->belongsTo(
            Question::class
        );
    }
}