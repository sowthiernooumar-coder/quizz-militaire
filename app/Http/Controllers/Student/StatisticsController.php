<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;

class StatisticsController extends Controller
{
    public function index()
    {
        $sessions = QuizSession::where(
            'user_id',
            auth()->id()
        )->get();

        $totalQuiz =
            $sessions->count();

        $averageScore =
            round(
                $sessions
                ->avg(
                    'score_percentage'
                ),
                2
            );

        $bestScore =
            $sessions
            ->max(
                'score_percentage'
            );

        return view(
            'student.statistics.index',
            compact(
                'totalQuiz',
                'averageScore',
                'bestScore'
            )
        );
    }
}