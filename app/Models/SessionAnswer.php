<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionAnswer extends Model
{
    protected $fillable = [

        'quiz_session_id',

        'question_id',

        'question_answer_id',

        'is_correct'
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

    public function selectedAnswer()
    {
        return $this->belongsTo(
            QuestionAnswer::class,
            'question_answer_id'
        );
    }
}