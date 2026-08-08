<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\LoginLog;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $promotionId = $user->promotion_id;

        $students = User::where('promotion_id', $promotionId)
            ->role('student')
            ->count();

        $quizCount = Quiz::where('creator_id', $user->id)->count();

        $quizSessions = QuizSession::whereHas(
            'user',
            fn($q) => $q->where('promotion_id', $promotionId)
        )->count();

        $averageScore = round(
            QuizSession::whereHas(
                'user',
                fn($q) => $q->where('promotion_id', $promotionId)->role('student')
            )->avg('score_percentage') ?? 0,
            2
        );

        // L2 : voit L1 + students | L1 : voit uniquement les students
        $allowedRoles = $user->hasRole('instructor_l2')
            ? ['instructor_l1', 'student']
            : ['student'];

        $promotionUserIds = User::where('promotion_id', $promotionId)
            ->whereHas('roles', fn($q) => $q->whereIn('name', $allowedRoles))
            ->pluck('id');

        $allRecentLogins = LoginLog::with('user.profile', 'user.roles')
            ->whereIn('user_id', $promotionUserIds)
            ->latest()
            ->take(100)
            ->get();

        $allRecentActivities = ActivityLog::with('user.profile', 'user.roles')
            ->whereIn('user_id', $promotionUserIds)
            ->latest()
            ->take(200)
            ->get();

        if ($user->hasRole('instructor_l2')) {
            $recentLogins = [
                'instructor_l1' => $allRecentLogins->filter(fn($l) => $l->user?->hasRole('instructor_l1'))->unique('user_id')->take(10)->values(),
                'student'       => $allRecentLogins->filter(fn($l) => $l->user?->hasRole('student'))->unique('user_id')->take(10)->values(),
            ];
            $recentActivities = [
                'instructor_l1' => $allRecentActivities->filter(fn($a) => $a->user?->hasRole('instructor_l1'))->take(10)->values(),
                'student'       => $allRecentActivities->filter(fn($a) => $a->user?->hasRole('student'))->take(10)->values(),
            ];
        } else {
            $recentLogins     = ['student' => $allRecentLogins->filter(fn($l) => $l->user?->hasRole('student'))->unique('user_id')->take(10)->values()];
            $recentActivities = ['student' => $allRecentActivities->filter(fn($a) => $a->user?->hasRole('student'))->take(10)->values()];
        }

        // Sessions actives
        $sessionLifetime = config('session.lifetime', 120);
        $onlineUserIds = \DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes($sessionLifetime)->timestamp)
            ->pluck('user_id')
            ->unique();

        // Graphique comparatif journalier des stagiaires (L1 et L2)
        $studentDailyChart = $this->dailyScoresByStudent($promotionId);

        return view('instructor.dashboard.index', compact(
            'students',
            'quizCount',
            'quizSessions',
            'averageScore',
            'recentLogins',
            'recentActivities',
            'onlineUserIds',
            'studentDailyChart'
        ));
    }

    /**
     * Score moyen journalier par stagiaire de la promotion,
     * sur les 14 derniers jours, formaté pour Chart.js.
     */
    private function dailyScoresByStudent(int $promotionId): array
    {
        $days = collect(range(13, 0))->map(
            fn($d) => Carbon::today()->subDays($d)->format('Y-m-d')
        );

        $studentList = User::where('promotion_id', $promotionId)
            ->role('student')
            ->with('profile')
            ->get();

        $rows = QuizSession::join('users', 'quiz_sessions.user_id', '=', 'users.id')
            ->where('users.promotion_id', $promotionId)
            ->whereHas('user', fn($q) => $q->role('student'))
            ->where('quiz_sessions.started_at', '>=', $days->first())
            ->selectRaw('quiz_sessions.user_id, DATE(quiz_sessions.started_at) as day, AVG(quiz_sessions.score_percentage) as avg_score')
            ->groupBy('quiz_sessions.user_id', 'day')
            ->get()
            ->groupBy('user_id');

        $colors = [
            '#0d6efd','#198754','#dc3545','#fd7e14',
            '#6f42c1','#20c997','#0dcaf0','#ffc107',
            '#d63384','#6c757d',
        ];

        $datasets = $studentList->values()->map(function (User $student, int $i) use ($rows, $days, $colors) {
            $scoresByDay = $rows->get($student->id, collect())->keyBy('day');
            $data = $days->map(fn($day) => round($scoresByDay->get($day)?->avg_score ?? 0, 2));
            $name = $student->first_name . ' ' . $student->last_name;

            return [
                'label'           => $name,
                'data'            => $data->values(),
                'borderColor'     => $colors[$i % count($colors)],
                'backgroundColor' => $colors[$i % count($colors)],
                'tension'         => 0.3,
                'fill'            => false,
            ];
        });

        return [
            'labels'   => $days->map(fn($d) => Carbon::parse($d)->format('d/m'))->values(),
            'datasets' => $datasets->values(),
        ];
    }
}
