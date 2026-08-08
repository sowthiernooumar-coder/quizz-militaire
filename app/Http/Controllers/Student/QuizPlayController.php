<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuestionAnswer;
use App\Models\QuizSession;
use App\Models\QuizSessionQuestion;
use App\Models\SessionAnswer;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuizPlayController extends Controller
{
    public function configuration()
    {
        $subjects =
            Subject::all();

        $levels = Quiz::LEVELS;

        return view(
            'student.quiz.configuration',
            compact('subjects', 'levels')
        );
    }

    public function start(Request $request)
    {
        $request->validate([

            'subject_id' => 'required|exists:subjects,id',

            'difficulty_level' => 'required|in:' . implode(',', Quiz::LEVELS),

            'number_of_questions' => 'required|integer|min:1'
        ]);

        $quiz = Quiz::where(
                'subject_id',
                $request->subject_id
            )
            ->where(
                'difficulty_level',
                $request->difficulty_level
            )
            ->first();

        if (!$quiz) {

            return back()->with(
                'error',
                'Aucun quiz trouvé pour cette matière et ce niveau.'
            );
        }

        $questions = $quiz->questions()
            ->inRandomOrder()
            ->take($request->number_of_questions)
            ->get();

        $session = QuizSession::create([

            'user_id' => auth()->id(),

            'quiz_id' => $quiz->id,

            'total_questions' =>
                $questions->count(),

            'started_at' => now()
        ]);

        foreach ($questions as $position => $question) {

            QuizSessionQuestion::create([

                'quiz_session_id' => $session->id,

                'question_id' => $question->id,

                'position' => $position + 1
            ]);
        }

        return redirect()->route(
            'student.quiz.show',
            $session
        );
    }

    public function show(QuizSession $session)
    {
        $questions = $session
            ->sessionQuestions()
            ->with('question.answers')
            ->orderBy('position')
            ->get()
            ->pluck('question');

        return view(
            'student.quiz.play',
            compact('session', 'questions')
        );
    }

    public function submit(Request $request, QuizSession $session)
    {
        $correct = 0;

        foreach ($request->answers ?? [] as $questionId => $answerId) {

            $selected = QuestionAnswer::find($answerId);

            $isCorrect = $selected?->is_correct ?? false;

            if ($isCorrect) {
                $correct++;
            }

            SessionAnswer::create([

                'quiz_session_id' => $session->id,

                'question_id' => $questionId,

                'question_answer_id' => $answerId,

                'is_correct' => $isCorrect
            ]);
        }

        $score = $session->total_questions > 0
            ? ($correct / $session->total_questions) * 100
            : 0;

        $session->update([

            'correct_answers' => $correct,

            'score_percentage' => $score,

            'finished_at' => now()
        ]);

        return redirect()->route(
            'student.quiz.result',
            $session
        );
    }

    public function result(QuizSession $session)
    {
        $questions = $session
            ->sessionQuestions()
            ->with('question.answers')
            ->orderBy('position')
            ->get()
            ->pluck('question');

        $session->load('answers.selectedAnswer');

        $answersByQuestion = $session->answers
            ->keyBy('question_id');

        return view(
            'student.quiz.result',
            compact('session', 'questions', 'answersByQuestion')
        );
    }
}
