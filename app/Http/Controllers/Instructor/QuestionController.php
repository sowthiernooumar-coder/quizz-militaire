<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Instructor\Concerns\ManagesQuestionAnswers;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    use ManagesQuestionAnswers;

    /**
     * Ajoute une nouvelle question (et ses 4 réponses) à un quiz existant.
     */
    public function store(Request $request, Quiz $quiz)
    {
        $this->authorizeQuizOwner($quiz);

        $validated = $this->validateQuestionData($request);

        $imagePath = $this->resolveQuestionImage($request);

        DB::transaction(function () use ($quiz, $validated, $imagePath) {

            $question = $quiz->questions()->create([
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'image' => $imagePath,
            ]);

            $this->saveAnswers($question, $validated);
        });

        return redirect()

            ->route(
                'instructor.quizzes.edit',
                $quiz
            )

            ->with(
                'success',
                'Question ajoutée'
            );
    }

    /**
     * Met à jour une question existante et ses 4 réponses.
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $this->authorizeQuizOwner($quiz);
        $this->authorizeQuestionBelongsToQuiz($quiz, $question);

        $validated = $this->validateQuestionData($request);

        $imagePath = $this->resolveQuestionImage($request, $question);

        DB::transaction(function () use ($question, $validated, $imagePath) {

            $question->update([
                'question' => $validated['question'],
                'explanation' => $validated['explanation'] ?? null,
                'image' => $imagePath,
            ]);

            $this->saveAnswers($question, $validated);
        });

        return redirect()

            ->route(
                'instructor.quizzes.edit',
                $quiz
            )

            ->with(
                'success',
                'Question modifiée'
            );
    }

    /**
     * Supprime une question (et ses réponses, par cascade) d'un quiz.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        $this->authorizeQuizOwner($quiz);
        $this->authorizeQuestionBelongsToQuiz($quiz, $question);

        if ($question->image) {
            Storage::disk('public')->delete($question->image);
        }

        $question->delete();

        return redirect()

            ->route(
                'instructor.quizzes.edit',
                $quiz
            )

            ->with(
                'success',
                'Question supprimée'
            );
    }

    private function authorizeQuizOwner(Quiz $quiz): void
    {
        abort_unless(
            $quiz->creator_id === auth()->id(),
            403
        );
    }

    private function authorizeQuestionBelongsToQuiz(Quiz $quiz, Question $question): void
    {
        abort_unless(
            $question->quiz_id === $quiz->id,
            404
        );
    }
}
