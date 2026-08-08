<?php

namespace App\Http\Controllers\Instructor\Concerns;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ManagesQuestionAnswers
{
    /**
     * Valide le texte de la question, l'explication, l'image, les 4 réponses
     * et l'index de la bonne réponse.
     */
    protected function validateQuestionData(Request $request): array
    {
        return $request->validate([

            'question' =>
                'required|string',

            'explanation' =>
                'nullable|string',

            'image' =>
                'nullable|image|max:2048',

            'answers' =>
                'required|array|size:4',

            'answers.*' =>
                'required|string',

            'correct_answer' =>
                'required|integer|min:0|max:3',
        ]);
    }

    /**
     * Gère l'image (facultative) d'une question : enregistre la nouvelle image
     * si une a été envoyée (en supprimant l'ancienne), sinon conserve l'existante.
     */
    protected function resolveQuestionImage(Request $request, ?Question $question = null): ?string
    {
        if ($request->hasFile('image')) {

            if ($question?->image) {
                Storage::disk('public')->delete($question->image);
            }

            return $request->file('image')->store('questions', 'public');
        }

        return $question?->image;
    }

    /**
     * Crée ou met à jour les 4 réponses d'une question, sans créer de doublon.
     */
    protected function saveAnswers(Question $question, array $validated): void
    {
        $existingAnswers = $question->answers()
            ->orderBy('display_order')
            ->get();

        foreach ($validated['answers'] as $index => $answerText) {

            $isCorrect = (int) $validated['correct_answer'] === $index;

            $answer = $existingAnswers->get($index);

            if ($answer) {
                $answer->update([
                    'answer_text' => $answerText,
                    'is_correct' => $isCorrect,
                    'display_order' => $index + 1,
                ]);
            } else {
                $question->answers()->create([
                    'answer_text' => $answerText,
                    'is_correct' => $isCorrect,
                    'display_order' => $index + 1,
                ]);
            }
        }
    }
}
