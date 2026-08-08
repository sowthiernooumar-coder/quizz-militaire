<?php

namespace App\Services;

use App\Models\Question;

class QuizService
{
    public function generateQuiz(
        $subjectId,
        $level,
        $count
    )
    {
        return Question::query()

            ->whereHas('quiz',
                fn($q) =>
                $q->where('subject_id',$subjectId)
                  ->where('level',$level))

            ->inRandomOrder()

            ->limit($count)

            ->get();
    }
}