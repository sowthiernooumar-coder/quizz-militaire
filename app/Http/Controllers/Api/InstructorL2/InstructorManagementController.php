<?php

namespace App\Http\Controllers\Api\InstructorL2;

use App\Http\Controllers\Controller;  
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;

class InstructorManagementController extends Controller
{
    public function assignSubject(
        Request $request
    )
    {
        $validated =
            $request->validate([

                'instructor_id' =>
                    'required|exists:users,id',

                'subject_id' =>
                    'required|exists:subjects,id'
            ]);

        $instructor =
            User::findOrFail(
                $validated['instructor_id']
            );

        $instructor
            ->subjects()
            ->syncWithoutDetaching([

                $validated['subject_id']

            ]);

        return response()->json([

            'message' =>
                'Matière attribuée'
        ]);
    }
}