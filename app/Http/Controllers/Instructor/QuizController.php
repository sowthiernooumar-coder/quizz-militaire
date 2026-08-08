<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Instructor\Concerns\ManagesQuestionAnswers;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    use ManagesQuestionAnswers;

    public function index()
    {
        $quizzes = Quiz::with([
            'subject',
            'creator',
            'questions.answers'
        ])

        ->latest()

        ->paginate(10);

        return view(
            'instructor.quizzes.index',
            compact('quizzes')
        );
    }

    public function create()
    {
        $subjects =
            auth()->user()->subjects;

        $levels = Quiz::LEVELS;

        return view(
            'instructor.quizzes.create',
            compact('subjects', 'levels')
        );
    }

    public function store(
        Request $request
    )
    {
        $quizData = $this->validateQuizFields(
            $request,
            auth()->user()->subjects->pluck('id')
        );

        $questionData = $this->validateQuestionData($request);

        $imagePath = $this->resolveQuestionImage($request);

        DB::transaction(function () use ($quizData, $questionData, $imagePath) {

            $quiz = Quiz::create([

                'title' => $quizData['title'],

                'subject_id' => $quizData['subject_id'],

                'difficulty_level' => $quizData['difficulty_level'],

                'creator_id' => auth()->id(),

                'is_active' => true,
            ]);

            $question = $quiz->questions()->create([
                'question' => $questionData['question'],
                'explanation' => $questionData['explanation'] ?? null,
                'image' => $imagePath,
            ]);

            $this->saveAnswers($question, $questionData);
        });

        return redirect()

            ->route(
                'instructor.quizzes.index'
            )

            ->with(
                'success',
                'Quiz créé'
            );
    }

    public function edit(
        Quiz $quiz
    )
    {
        $subjects =
            auth()->user()->subjects;

        if (! $subjects->contains('id', $quiz->subject_id)) {
            $subjects->push($quiz->subject);
        }

        $levels = Quiz::LEVELS;

        $questions = $quiz->questions()
            ->with('answers')
            ->get();

        return view(
            'instructor.quizzes.edit',
            compact(
                'quiz',
                'subjects',
                'levels',
                'questions'
            )
        );
    }

    public function update(
        Request $request,
        Quiz $quiz
    )
    {
        $assignedSubjectIds =
            auth()->user()->subjects
                ->pluck('id')
                ->push($quiz->subject_id)
                ->unique();

        $validated = $this->validateQuizFields(
            $request,
            $assignedSubjectIds
        );

        $quiz->update($validated);

        return redirect()

            ->route(
                'instructor.quizzes.edit',
                $quiz
            )

            ->with(
                'success',
                'Quiz modifié'
            );
    }

    public function destroy(
        Quiz $quiz
    )
    {
        $quiz->delete();

        return back()

            ->with(
                'success',
                'Quiz supprimé'
            );
    }

    /**
     * Valide les informations propres au quiz : titre, matière, niveau.
     */
    private function validateQuizFields(Request $request, $assignedSubjectIds): array
    {
        return $request->validate([

            'title' =>
                'required|string|max:255',

            'subject_id' =>
                'required|in:' . $assignedSubjectIds->implode(','),

            'difficulty_level' =>
                'required|in:' . implode(',', Quiz::LEVELS),

        ], [
            'subject_id.in' =>
                'Vous ne pouvez utiliser qu\'une matière qui vous est assignée.',
        ]);
    }
}
