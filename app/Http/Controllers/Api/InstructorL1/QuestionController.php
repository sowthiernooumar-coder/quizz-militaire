<?php

namespace App\Http\Controllers\Api\InstructorL1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated =
            $request->validate([

                'quiz_id' =>
                    'required|exists:quizzes,id',

                'question' =>
                    'required',

                'option_a' =>
                    'required',

                'option_b' =>
                    'required',

                'option_c' =>
                    'required',

                'option_d' =>
                    'required',

                'correct_answer' =>
                    'required|in:A,B,C,D'
            ]);

        $question =
            Question::create(
                $validated
            );

        return response()->json([
            'message' =>
                'Question ajoutée'
        ]);
    }
}
