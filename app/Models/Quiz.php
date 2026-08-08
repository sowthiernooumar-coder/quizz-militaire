<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    public const LEVELS = [
        'CDE',
        'CDG',
        'CDS',
    ];

    protected $fillable = [

        'title',

        'description',

        'subject_id',

        'creator_id',

        'difficulty_level',

        'is_active'
    ];

    protected $casts = [

        'is_active' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function subject()
    {
        return $this->belongsTo(
            Subject::class
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'creator_id'
        );
    }

    public function questions()
    {
        return $this->hasMany(
            Question::class
        );
    }

    public function sessions()
    {
        return $this->hasMany(
            QuizSession::class
        );
    }
}