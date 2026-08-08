<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorManagementController extends Controller
{
    /**
     * Liste l'instructeur L2 connecté ainsi que les instructeurs L1
     * de la même promotion, pour attribution des matières.
     */
    public function index()
    {
        $self = auth()->user()->load('subjects');

        $promotionId = $self->promotion_id;

        $instructors = User::role('instructor_l1')
            ->where('promotion_id', $promotionId)
            ->with('subjects')
            ->get();

        $subjects = Subject::all();

        return view(
            'instructor.management.index',
            compact('self', 'instructors', 'subjects')
        );
    }

    /**
     * Met à jour les matières attribuées à un instructeur (soi-même ou un L1
     * de la même promotion).
     */
    public function update(Request $request, User $instructor)
    {
        abort_unless(
            $this->canManageSubjectsFor($instructor),
            403
        );

        $validated = $request->validate([
            'subject_ids'   => 'required|array|min:1',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $instructor->subjects()->sync($validated['subject_ids']);

        return redirect()
            ->route('instructor.dashboard')
            ->with(
                'success',
                'Matières mises à jour pour ' . $instructor->first_name . ' ' . $instructor->last_name
            );
    }

    /**
     * L'instructeur L2 connecté peut gérer ses propres matières,
     * ou celles des instructeurs L1 de sa promotion.
     */
    private function canManageSubjectsFor(User $instructor): bool
    {
        $authUser = auth()->user();

        if ($instructor->id === $authUser->id) {
            return true;
        }

        return $instructor->hasRole('instructor_l1')
            && $instructor->promotion_id === $authUser->promotion_id;
    }
}
