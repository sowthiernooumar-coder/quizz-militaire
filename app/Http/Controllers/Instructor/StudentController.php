<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $promotionId =
            auth()->user()
            ->promotion_id;

        $students = User::where(
            'promotion_id',
            $promotionId
        )

        ->role('student')

        ->paginate(20);

        return view(
            'instructor.students.index',
            compact('students')
        );
    }

    /**
     * Liste les sessions de quiz passées par un stagiaire de la promotion
     * de l'instructeur connecté.
     */
    public function sessions(User $student)
    {
        $this->authorizeSameStudent($student);

        $sessions = QuizSession::where('user_id', $student->id)
            ->with('quiz')
            ->latest('started_at')
            ->paginate(20);

        return view(
            'instructor.students.sessions',
            compact('student', 'sessions')
        );
    }

    /**
     * Réinitialise (supprime) une session de quiz d'un stagiaire,
     * lui permettant de repasser le quiz.
     */
    public function resetSession(User $student, QuizSession $session)
    {
        $this->authorizeSameStudent($student);

        abort_unless(
            $session->user_id === $student->id,
            404
        );

        $session->delete();

        return back()->with(
            'success',
            'Session de quiz réinitialisée'
        );
    }

    /**
     * L'instructeur ne peut agir que sur les stagiaires de sa propre promotion.
     */
    private function authorizeSameStudent(User $student): void
    {
        abort_unless(
            $student->hasRole('student')
                && $student->promotion_id === auth()->user()->promotion_id,
            403
        );
    }
}
