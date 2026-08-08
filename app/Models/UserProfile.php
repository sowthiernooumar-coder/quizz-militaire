<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    public const EDUCATION_LEVELS = [
        'BAC',
        'BAC+2',
        'BAC+3',
        'BAC+4',
        'BAC+5',
        'BAC+7',
    ];

    public const PREVIOUS_PROFESSIONS = [
        'Ancien pékin',
        'Ancien enfant de troupe',
        'Ancien militaire',
    ];

    protected $fillable = [

        'user_id',

        'avatar',

        'birth_place',

        'birth_date',

        'country',

        'marital_status',

        'gender',

        'education_level',

        'previous_profession',
    ];

    protected $casts = [

        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}
