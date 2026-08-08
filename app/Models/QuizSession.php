<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    protected $fillable = [

        'user_id',

        'quiz_id',

        'total_questions',

        'correct_answers',

        'score_percentage',

        'started_at',

        'finished_at'
    ];

    protected $casts = [

        'started_at' => 'datetime',

        'finished_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function quiz()
    {
        return $this->belongsTo(
            Quiz::class
        );
    }

    public function answers()
    {
        return $this->hasMany(
            SessionAnswer::class
        );
    }

    public function sessionQuestions()
    {
        return $this->hasMany(
            QuizSessionQuestion::class
        );
    }
}