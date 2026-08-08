<?php

namespace App\Http\Controllers\Api\InstructorL1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([

            'my_quizzes' =>

                Quiz::where(
                    'creator_id',
                    auth()->id()
                )->count(),

            'my_questions' =>

                Question::whereHas(
                    'quiz',
                    function($query){

                        $query->where(
                            'creator_id',
                            auth()->id()
                        );
                    }
                )->count()
        ]);
    }
}