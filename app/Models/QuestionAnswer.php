<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    protected $fillable = [

        'question_id',

        'answer_text',

        'is_correct',

        'display_order'
    ];

    public function question()
    {
        return $this->belongsTo(
            Question::class
        );
    }
}