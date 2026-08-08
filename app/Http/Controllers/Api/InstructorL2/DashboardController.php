<?php

namespace App\Http\Controllers\Api\InstructorL2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $promotionId =
            auth()->user()->promotion_id;

        return response()->json([

            'promotion_id' =>
                $promotionId,

            'l1_count' =>

                User::role(
                    'instructor_l1'
                )

                ->where(
                    'promotion_id',
                    $promotionId
                )

                ->count(),

            'students_count' =>

                User::role(
                    'student'
                )

                ->where(
                    'promotion_id',
                    $promotionId
                )

                ->count()
        ]);
    }
}