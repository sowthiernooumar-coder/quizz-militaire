<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [

        'name',

        'description'
    ];

    public function quizzes()
    {
        return $this->hasMany(
            Quiz::class
        );
    }

    public function instructors()
    {
        return $this->belongsToMany(
            User::class,
            'instructor_subjects'
        );
    }
}