<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\SessionAnswer;
use App\Models\QuizSessionQuestion;
use App\Models\Quiz;

class QuizPlayController extends Controller
{
    //Start a new quiz session and return the questions
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

            return back()

                ->with(
                    'error',
                    'Aucun quiz trouvé'
                );
        }

        $session = QuizSession::create([

            'user_id' => auth()->id(),

            'quiz_id' => $quiz->id,

            'total_questions' =>
                $request->number_of_questions,

            'started_at' => now()
        ]);

        session([
            'quiz_session_id' =>
                $session->id
        ]);

        return redirect()->route(

            'student.quiz.show',

            $session
        );
    }

    //Get the next question for the quiz session
    public function nextQuestion(
    QuizSession $session)
    {
        $question =

            $session
                ->sessionQuestions()

                ->with('question')

                ->whereNotIn(
                    'question_id',

                    SessionAnswer::where(
                        'quiz_session_id',
                        $session->id
                    )->pluck('question_id')
                )

                ->orderBy('position')

                ->first();

        if (!$question) {

            return response()->json([

                'finished' => true
            ]);
        }

        return response()->json([

            'finished' => false,

            'question' =>
                $question->question
        ]);
    }

    //Submit the quiz answers and calculate the score
    public function submit(Request $request, QuizSession $session)
    {
        $correct = 0;

        foreach(
            $request->answers
            as $questionId => $answerId
        )
        {
            $selected =

                \App\Models\QuestionAnswer

                ::find($answerId);

            $isCorrect =
                $selected->is_correct;

            if($isCorrect)
            {
                $correct++;
            }

            SessionAnswer::create([

                'quiz_session_id' =>
                    $session->id,

                'question_id' =>
                    $questionId,

                'question_answer_id' =>
                    $answerId,

                'is_correct' =>
                    $isCorrect
            ]);
        }

        $score =

            (
                $correct
                /
                $session->total_questions
            )

            * 100;

        $session->update([

            'correct_answers' =>
                $correct,

            'score_percentage' =>
                $score,

            'finished_at' =>
                now()
        ]);

        return redirect()->route(

            'student.quiz.result',

            $session
        );
    }

    //Show the quiz result for a specific session
    public function show(QuizSession $session)
    {
        $questions =

            $session

            ->quiz

            ->questions()

            ->with('answers')

            ->inRandomOrder()

            ->take(
                $session->total_questions
            )

            ->get();

        return view(

            'student.quiz.play',

            compact(
                'session',
                'questions'
            )
        );
    }

    //Show the quiz dashboard with statistics for the authenticated student
    public function dashboard()
    {
        $sessions =
            QuizSession::where(
                'user_id',
                auth()->id()
            );

        return response()->json([

            'quizzes_taken' =>
                $sessions->count(),

            'average_score' =>
                round(
                    $sessions->avg(
                        'score_percentage'
                    ),
                    2
                ),

            'best_score' =>
                $sessions->max(
                    'score_percentage'
                ),

            'last_score' =>
                optional(
                    $sessions
                        ->latest()
                        ->first()
                )->score_percentage
        ]);
    }

    public function result(QuizSession $session)
    {
        $session->load(

            'answers.question',

            'answers.selectedAnswer'
        );

        return view(

            'student.quiz.result',

            compact('session')
        );
    }
    
}
