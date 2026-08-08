<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\LoginLog;
use App\Models\ActivityLog;
use App\Models\Promotion;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalStudents =
            User::role('student')->count();

        $totalLevel1 =
            User::role('instructor_l1')->count();

        $totalLevel2 =
            User::role('instructor_l2')->count();

        $totalQuizzes =
            Quiz::count();

        $totalQuestions =
            Question::count();

        $totalSessions =
            QuizSession::count();

        // Dernières connexions groupées par rôle
        $allRecentLogins = LoginLog::with('user.profile', 'user.roles')
            ->latest()
            ->take(100) // large pool pour garantir des entrées dans chaque groupe
            ->get();

        $recentLogins = [
            'instructor_l2' => $allRecentLogins
                ->filter(fn($l) => $l->user && $l->user->hasRole('instructor_l2'))
                ->unique('user_id')
                ->take(10)
                ->values(),
            'instructor_l1' => $allRecentLogins
                ->filter(fn($l) => $l->user && $l->user->hasRole('instructor_l1'))
                ->unique('user_id')
                ->take(10)
                ->values(),
            'student' => $allRecentLogins
                ->filter(fn($l) => $l->user && $l->user->hasRole('student'))
                ->unique('user_id')
                ->take(10)
                ->values(),
        ];

        // Activités récentes groupées par rôle
        $allRecentActivities = ActivityLog::with('user.profile', 'user.roles')
            ->latest()
            ->take(200)
            ->get();

        $recentActivities = [
            'instructor_l2' => $allRecentActivities
                ->filter(fn($a) => $a->user && $a->user->hasRole('instructor_l2'))
                ->take(10)
                ->values(),
            'instructor_l1' => $allRecentActivities
                ->filter(fn($a) => $a->user && $a->user->hasRole('instructor_l1'))
                ->take(10)
                ->values(),
            'student' => $allRecentActivities
                ->filter(fn($a) => $a->user && $a->user->hasRole('student'))
                ->take(10)
                ->values(),
        ];

        // IDs des utilisateurs ayant une session active dans la table sessions
        $sessionLifetime = config('session.lifetime', 120); // en minutes
        $onlineUserIds = \DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes($sessionLifetime)->timestamp)
            ->pluck('user_id')
            ->unique();

        $averageScore = round(
            QuizSession::avg(
                'score_percentage'
            ) ?? 0,
            2
        );

        $bestScore =
            QuizSession::max(
                'score_percentage'
            ) ?? 0;

        $promotionScores = $this->scoresByPromotion();

        $promotionDailyChart = $this->dailyScoresByPromotion();

        return view(
            'admin.dashboard.index',
            compact(
                'totalUsers',
                'totalStudents',
                'totalLevel1',
                'totalLevel2',
                'totalQuizzes',
                'totalQuestions',
                'totalSessions',
                'recentLogins',
                'recentActivities',
                'averageScore',
                'bestScore',
                'promotionScores',
                'promotionDailyChart',
                'onlineUserIds'
            )
        );
    }

    /**
     * Score moyen et meilleur score, par promotion.
     */
    private function scoresByPromotion()
    {
        return Promotion::all()->map(function (Promotion $promotion) {

            $sessions = QuizSession::join(
                'users',
                'quiz_sessions.user_id',
                '=',
                'users.id'
            )->where(
                'users.promotion_id',
                $promotion->id
            );

            return [
                'name' => $promotion->name,
                'average' => round(
                    $sessions->avg('quiz_sessions.score_percentage') ?? 0,
                    2
                ),
                'best' => $sessions->max('quiz_sessions.score_percentage') ?? 0,
            ];
        });
    }

    /**
     * Score moyen journalier par promotion, sur les 14 derniers jours,
     * formaté pour Chart.js.
     */
    private function dailyScoresByPromotion(): array
    {
        $days = collect(range(13, 0))->map(
            fn ($daysAgo) => Carbon::today()->subDays($daysAgo)->format('Y-m-d')
        );

        $promotions = Promotion::all();

        $rows = QuizSession::join(
            'users',
            'quiz_sessions.user_id',
            '=',
            'users.id'
        )
            ->whereNotNull('users.promotion_id')
            ->where('quiz_sessions.started_at', '>=', $days->first())
            ->selectRaw(
                'users.promotion_id as promotion_id, DATE(quiz_sessions.started_at) as day, AVG(quiz_sessions.score_percentage) as avg_score'
            )
            ->groupBy('users.promotion_id', 'day')
            ->get()
            ->groupBy('promotion_id');

        $colors = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#20c997'];

        $datasets = $promotions->values()->map(function (Promotion $promotion, int $index) use ($rows, $days, $colors) {

            $scoresByDay = $rows->get($promotion->id, collect())
                ->keyBy('day');

            $data = $days->map(
                fn ($day) => round($scoresByDay->get($day)?->avg_score ?? 0, 2)
            );

            return [
                'label' => $promotion->name,
                'data' => $data->values(),
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => $colors[$index % count($colors)],
                'tension' => 0.3,
                'fill' => false,
            ];
        });

        return [
            'labels' => $days->map(
                fn ($day) => Carbon::parse($day)->format('d/m')
            )->values(),
            'datasets' => $datasets->values(),
        ];
    }
}