<?php

namespace App\Http\Controllers\Api\InstructorL1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Http\Resources\QuizResource;
use App\Models\User;
use App\Notifications\NewQuizNotification;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with([
            'subject',
            'creator'
        ])
        ->latest()
        ->paginate(20);

        return QuizResource::collection(
            $quizzes
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'title' =>
                'required|string|max:255',

            'subject_id' =>
                'required|exists:subjects,id',

            'difficulty_level' =>
                'required|in:' . implode(',', Quiz::LEVELS)
        ]);

        $validated['creator_id'] =
            auth()->id();

        $quiz = Quiz::create($validated);

        $students = User::role(
            'student'
        )->get();

        foreach (
            $students as $student
        )
        {
            $student->notify(

                new NewQuizNotification(
                    $quiz->title
                )
            );
        }

        return response()->json([

            'message' =>
                'Quiz créé avec succès',

            'data' =>
                new QuizResource($quiz)

        ],201);
    }

    public function show(Quiz $quiz)
    {
        return new QuizResource(
            $quiz->load([
                'subject',
                'creator'
            ])
        );
    }

    public function update(
    Request $request,
    Quiz $quiz)
    {
        if (
            auth()->check()
            &&
            auth()->user()->hasRole(
                'instructor_l1'
            )
            &&
            $quiz->creator_id !== auth()->id()
        )
        {
            abort(403);
        }

        $quiz->update(
            $request->validate([
                'title' => 'required',
                'difficulty_level' => 'required|in:' . implode(',', Quiz::LEVELS)
            ])
        );

        return response()->json([
            'message' =>
                'Quiz modifié'
        ]);
    }

    public function destroy(Quiz $quiz)
    {
        if (
            auth()->user()->hasRole(
                'instructor_l1'
            )
            &&
            $quiz->creator_id !== auth()->id()
        )
        {
            abort(403);
        }

        $quiz->delete();

        return response()->json([
            'message' =>
                'Quiz supprimé'
        ]);
    }
}
