<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;

class DashboardController extends Controller
{
    public function index()
    {
        $sessions = QuizSession::where(
            'user_id',
            auth()->id()
        );

        $quizzesTaken = $sessions->count();

        $averageScore = round(
            $sessions->avg('score_percentage') ?? 0,
            2
        );

        $bestScore = $sessions->max('score_percentage') ?? 0;

        $lastSession = QuizSession::where(
            'user_id',
            auth()->id()
        )
            ->latest()
            ->first();

        return view(
            'student.dashboard.index',
            compact(
                'quizzesTaken',
                'averageScore',
                'bestScore',
                'lastSession'
            )
        );
    }
}
