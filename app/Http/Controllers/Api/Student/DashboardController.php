<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\QuizSession;

class DashboardController extends Controller
{
    public function index()
    {
        $sessions = QuizSession::where(
            'user_id',
            auth()->id()
        );

        return response()->json([

            'quizzes_taken' =>
                $sessions->count(),

            'average_score' =>
                round(
                    $sessions->avg(
                        'score_percentage'
                    ),
                    2
                ),

            'best_score' =>
                $sessions->max(
                    'score_percentage'
                ),

            'last_score' =>
                optional(
                    $sessions
                        ->latest()
                        ->first()
                )->score_percentage
        ]);
    }
}