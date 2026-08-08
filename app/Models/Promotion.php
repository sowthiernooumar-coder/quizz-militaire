<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [

        'name',

        'description',

        'start_date',

        'end_date'
    ];

    public function users()
    {
        return $this->hasMany(
            User::class
        );
    }

    public function students()
    {
        return $this->hasMany(User::class)
                    ->role('student');
    }

    public function instructors()
    {
        return $this->hasMany(User::class)
                    ->role([
                        'instructor_l1',
                        'instructor_l2'
                    ]);
    }
}