<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;

class HistoryController extends Controller
{
    public function index()
    {
        $sessions = QuizSession::with([
            'quiz.subject'
        ])

        ->where(
            'user_id',
            auth()->id()
        )

        ->latest()

        ->paginate(15);

        return view(
            'student.history.index',
            compact('sessions')
        );
    }

    public function show(
        QuizSession $session
    )
    {
        $session->load([

            'quiz.subject',

            'answers.question',

            'answers.selectedAnswer'
        ]);

        return view(
            'student.history.show',
            compact('session')
        );
    }
}