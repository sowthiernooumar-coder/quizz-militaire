<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorSubjectController extends Controller
{
    /**
     * Liste tous les instructeurs (L1 et L2) pour attribution des matières.
     */
    public function index()
    {
        $instructors = User::role(['instructor_l1', 'instructor_l2'])
            ->with(['subjects', 'promotion'])
            ->orderBy('first_name')
            ->get();

        $subjects = Subject::all();

        return view(
            'admin.instructors.index',
            compact('instructors', 'subjects')
        );
    }

    /**
     * Met à jour les matières attribuées à un instructeur (L1 ou L2).
     */
    public function update(Request $request, User $instructor)
    {
        abort_unless(
            $instructor->hasRole(['instructor_l1', 'instructor_l2']),
            403
        );

        $validated = $request->validate([
            'subject_ids' => 'array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $instructor->subjects()->sync(
            $validated['subject_ids'] ?? []
        );

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                'Matières mises à jour pour ' . $instructor->first_name . ' ' . $instructor->last_name
            );
    }
}
