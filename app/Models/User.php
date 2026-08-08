<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /**
     * @extends \Illuminate\Foundation\Auth\User
     */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'matricule',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'grade',
        'rank',
        'service_number',
        'promotion_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function promotion()
    {
        return $this->belongsTo(
            Promotion::class
        );
    }

    public function quizzes()
    {
        return $this->hasMany(
            Quiz::class,
            'creator_id'
        );
    }

    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'instructor_subjects'
        );
    }

    public function quizSessions()
    {
        return $this->hasMany(
            QuizSession::class
        );
    }

    public function samePromotionAs(User $user)
    {
        return $this->promotion_id === $user->promotion_id;
    }
    
    public function loginLogs()
    {
        return $this->hasMany(
            LoginLog::class
        );
    }

    public function activityLogs()
    {
        return $this->hasMany(
            ActivityLog::class
        );
    }

    public function profile()
    {
        return $this->hasOne(
            UserProfile::class
        );
    }
}
