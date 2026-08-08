<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizSession;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    //Top 10 des étudiants avec les meilleures moyennes
    public function topStudents()
    {
        $students = User::query()

            ->role('student')

            ->join(
                'quiz_sessions',
                'users.id',
                '=',
                'quiz_sessions.user_id'
            )

            ->select(

                'users.id',

                'users.first_name',

                'users.last_name',

                DB::raw(
                    'AVG(quiz_sessions.score_percentage)
                    as average_score'
                )
            )

            ->groupBy(
                'users.id',
                'users.first_name',
                'users.last_name'
            )

            ->orderByDesc(
                'average_score'
            )

            ->limit(10)

            ->get();

        return response()->json(
            $students
        );
    }

    //Statistique par matière
    public function subjectStatistics()
    {
        $stats = QuizSession::query()

            ->join(
                'quizzes',
                'quiz_sessions.quiz_id',
                '=',
                'quizzes.id'
            )

            ->join(
                'subjects',
                'quizzes.subject_id',
                '=',
                'subjects.id'
            )

            ->select(

                'subjects.name',

                DB::raw(
                    'AVG(quiz_sessions.score_percentage)
                    as average_score'
                ),

                DB::raw(
                    'COUNT(*)
                    as total_sessions'
                )
            )

            ->groupBy(
                'subjects.name'
            )

            ->get();

        return response()->json(
            $stats
        );
    }

    //Statistique par promotion
    public function promotionStatistics()
    {
        $stats = QuizSession::query()

            ->join(
                'users',
                'quiz_sessions.user_id',
                '=',
                'users.id'
            )

            ->join(
                'promotions',
                'users.promotion_id',
                '=',
                'promotions.id'
            )

            ->select(

                'promotions.name',

                DB::raw(
                    'AVG(quiz_sessions.score_percentage)
                    as average_score'
                ),

                DB::raw(
                    'COUNT(*) as total_quizzes'
                )
            )

            ->groupBy(
                'promotions.name'
            )

            ->get();

        return response()->json(
            $stats
        );
    }
    //statistique de l'évolution mensuelle des scores moyens
    public function monthlyEvolution()
    {
        $stats = QuizSession::query()

            ->select(

                DB::raw(
                    'MONTH(created_at)
                    as month'
                ),

                DB::raw(
                    'AVG(score_percentage)
                    as average_score'
                )
            )

            ->groupBy(
                'month'
            )

            ->orderBy(
                'month'
            )

            ->get();

        return response()->json(
            $stats
        );
    }

    //Taux de réussite global
    public function successRate()
    {
        $average =
            QuizSession::avg(
                'score_percentage'
            );

        return response()->json([

            'success_rate' =>
                round(
                    $average,
                    2
                )
        ]);
    }
}