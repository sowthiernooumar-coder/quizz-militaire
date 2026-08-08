<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);

        return view(
            'admin.subjects.index',
            compact('subjects')
        );
    }

    public function create()
    {
        return view(
            'admin.subjects.create'
        );
    }

    public function store(Request $request)
    {
        $validated =
            $request->validate([

                'name' =>
                    'required|string|max:255',

                'description' =>
                    'nullable|string'
            ]);

        Subject::create($validated);

        return redirect()

            ->route(
                'admin.subjects.index'
            )

            ->with(
                'success',
                'Matière créée avec succès.'
            );
    }

    public function edit(
        Subject $subject
    )
    {
        return view(
            'admin.subjects.edit',
            compact('subject')
        );
    }

    public function update(
        Request $request,
        Subject $subject
    )
    {
        $validated =
            $request->validate([

                'name' =>
                    'required|string|max:255',

                'description' =>
                    'nullable|string'
            ]);

        $subject->update(
            $validated
        );

        return redirect()

            ->route(
                'admin.subjects.index'
            )

            ->with(
                'success',
                'Matière modifiée.'
            );
    }

    public function destroy(
        Subject $subject
    )
    {
        $subject->delete();

        return back()

            ->with(
                'success',
                'Matière supprimée.'
            );
    }
}