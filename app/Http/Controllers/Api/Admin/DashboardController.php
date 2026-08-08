<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\UserActivity;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([

            'total_users' =>
                User::count(),

            'students' =>
                User::role('student')->count(),

            'instructor_l1' =>
                User::role('instructor_l1')->count(),

            'instructor_l2' =>
                User::role('instructor_l2')->count(),

            'quizzes' =>
                Quiz::count(),

            'questions' =>
                Question::count(),

            'quiz_sessions' =>
                QuizSession::count(),

            'online_users' =>
                UserActivity::where(
                    'last_activity_at',
                    '>=',
                    now()->subMinutes(5)
                )->count()
        ]);
    }
}