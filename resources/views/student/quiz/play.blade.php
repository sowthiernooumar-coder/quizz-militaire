@extends('layouts.dashboard')

@section('content')

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=black-ops-one:400|share-tech-mono:400" rel="stylesheet" />

<style>
    .quiz-page-bg {
        margin: -1.5rem;
        flex: 1;
        background-color: #2e3b1f;
        background-image:
            radial-gradient(ellipse at top left, rgba(106, 122, 64, .25), transparent 55%),
            radial-gradient(ellipse at bottom right, rgba(20, 25, 12, .4), transparent 55%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .quiz-arena {
        width: 100%;
        max-width: 760px;
        margin: 0 auto;
        padding: clamp(1rem, 4vw, 2rem) clamp(0.75rem, 4vw, 1.75rem) clamp(1.25rem, 4vw, 2.5rem);
        background: #14160f;
        background-image:
            radial-gradient(ellipse at top left, rgba(106, 122, 64, .25), transparent 55%),
            radial-gradient(ellipse at bottom right, rgba(60, 70, 35, .3), transparent 55%);
        border: 1px solid #3a4024;
        border-radius: .75rem;
        box-shadow: 0 0 2.5rem rgba(0, 229, 255, .35), 0 1rem 2.5rem rgba(0, 0, 0, .45);
        color: #d9d6c3;
    }

    .quiz-arena .btn-link {
        color: #a3b18a;
    }

    .quiz-mil-title {
        font-family: 'Black Ops One', system-ui, sans-serif;
        letter-spacing: .04em;
        color: #c9a227;
        text-transform: uppercase;
    }

    .quiz-progress-label {
        font-family: 'Share Tech Mono', monospace;
        font-weight: 600;
        letter-spacing: .08em;
        color: #a3b18a;
        text-transform: uppercase;
    }

    .quiz-timer-bar {
        height: 10px;
        border-radius: 3px;
        background: #2b2e20;
        overflow: hidden;
        margin-bottom: .5rem;
        border: 1px solid #3a4024;
    }

    .quiz-timer-bar-fill {
        height: 100%;
        background: #6b8e23;
        transition: width 1s linear, background-color .3s ease;
    }

    .quiz-timer-bar-fill.is-warning {
        background: #c9a227;
    }

    .quiz-timer-bar-fill.is-danger {
        background: #b33a3a;
    }

    .quiz-progress.progress {
        background-color: #2b2e20;
        border: 1px solid #3a4024;
    }

    .quiz-progress .progress-bar {
        background-color: #6b8e23;
    }

    .quiz-question-card {
        border: 1px solid #3a4024;
        border-radius: .6rem;
        background: #1c1f15;
        box-shadow: inset 0 0 0 1px rgba(106, 122, 64, .15), 0 .5rem 1.5rem rgba(0, 0, 0, .4);
        animation: quiz-fade-in .35s ease;
    }

    @keyframes quiz-fade-in {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .quiz-question-text {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #ece9d8;
    }

    .quiz-question-image {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
        max-height: 320px;
        border-radius: .5rem;
        border: 1px solid #3a4024;
    }

    .quiz-answer-btn {
        width: 100%;
        text-align: left;
        padding: .9rem 1.25rem;
        border-radius: .5rem;
        border: 2px solid #3a4024;
        background: #20231a;
        color: #d9d6c3;
        margin-bottom: .75rem;
        font-size: 1.05rem;
        transition: all .15s ease;
    }

    .quiz-answer-btn:hover:not(:disabled) {
        border-color: #6b8e23;
        background: #2a2f1f;
        transform: translateX(2px);
    }

    .quiz-answer-btn.is-correct {
        border-color: #2e7d32;
        background: #2e7d32;
        color: #fff;
        font-weight: 600;
    }

    .quiz-answer-btn.is-incorrect {
        border-color: #b33a3a;
        background: #b33a3a;
        color: #fff;
        font-weight: 600;
    }

    .quiz-answer-btn:disabled {
        cursor: default;
    }

    .quiz-timer-number {
        font-family: 'Share Tech Mono', monospace;
        font-size: 1.2rem;
        font-weight: 700;
        min-width: 2.5rem;
        text-align: right;
        color: #c9a227;
    }

    .quiz-spinner-text {
        color: #a3b18a;
        font-family: 'Share Tech Mono', monospace;
        letter-spacing: .05em;
    }
</style>

<div class="quiz-page-bg">
<div class="quiz-arena">

    <a href="{{ route('student.dashboard') }}" class="btn btn-link mb-3 px-0">
        &larr; Retour
    </a>

    <h5 class="quiz-mil-title mb-3">🎖️ Mission Quiz</h5>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="quiz-progress-label" id="quiz-progress-label">
            Question 1 / {{ $questions->count() }}
        </span>
        <span class="quiz-timer-number" id="quiz-timer-number">20s</span>
    </div>

    <div class="progress quiz-progress mb-1" style="height: 10px;">
        <div class="progress-bar" id="quiz-progress-bar" style="width: 0%"></div>
    </div>

    <div class="quiz-timer-bar mt-3">
        <div class="quiz-timer-bar-fill" id="quiz-timer-bar" style="width: 100%"></div>
    </div>

    <div id="quiz-question-container" class="mt-4"></div>

    <form
        id="quiz-submit-form"
        method="POST"
        action="{{ route('student.quiz.submit', $session) }}"
        style="display:none"
    >
        @csrf
        <div id="quiz-answers-inputs"></div>
    </form>

</div>
</div>

@php
    $playQuestions = $questions->map(function ($question) {
        return [
            'id' => $question->id,
            'question' => $question->question,
            'image' => $question->image ? asset('storage/' . $question->image) : null,
            'answers' => $question->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'text' => $answer->answer_text,
                    'is_correct' => (bool) $answer->is_correct,
                ];
            })->values(),
        ];
    })->values();
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Étend le fond vert militaire sur toute la hauteur disponible
    // entre le header et le footer, sans laisser de bande blanche.
    (function extendBackgroundToFooter() {
        const mainEl = document.querySelector('main.flex-grow-1');
        const containerEl = mainEl ? mainEl.querySelector(':scope > .container-fluid') : null;

        if (mainEl) {
            mainEl.style.display = 'flex';
            mainEl.style.flexDirection = 'column';
        }

        if (containerEl) {
            containerEl.style.flex = '1';
            containerEl.style.display = 'flex';
            containerEl.style.flexDirection = 'column';
        }
    })();

    const questions = @json($playQuestions);

    const TIME_PER_QUESTION = 20;

    let currentIndex = 0;
    let timeLeft = TIME_PER_QUESTION;
    let timerHandle = null;
    let locked = false;
    const answers = {};

    const progressLabel = document.getElementById('quiz-progress-label');
    const progressBar = document.getElementById('quiz-progress-bar');
    const timerNumber = document.getElementById('quiz-timer-number');
    const timerBar = document.getElementById('quiz-timer-bar');
    const questionContainer = document.getElementById('quiz-question-container');
    const answersInputs = document.getElementById('quiz-answers-inputs');
    const submitForm = document.getElementById('quiz-submit-form');

    function renderQuestion() {
        const question = questions[currentIndex];
        locked = false;

        progressLabel.textContent = 'Question ' + (currentIndex + 1) + ' / ' + questions.length;
        progressBar.style.width = Math.round((currentIndex / questions.length) * 100) + '%';

        let html = '<div class="card quiz-question-card"><div class="card-body p-4">';
        html += '<p class="quiz-question-text">' + escapeHtml(question.question) + '</p>';

        if (question.image) {
            html += '<img src="' + question.image + '" alt="" class="quiz-question-image mb-3">';
        }

        question.answers.forEach(function (answer) {
            html += '<button type="button" class="quiz-answer-btn" data-answer-id="' + answer.id + '" data-correct="' + (answer.is_correct ? '1' : '0') + '">'
                + escapeHtml(answer.text)
                + '</button>';
        });

        html += '</div></div>';
        questionContainer.innerHTML = html;

        questionContainer.querySelectorAll('.quiz-answer-btn').forEach(function (button, answerIndex) {
            button.addEventListener('click', function () {
                selectAnswer(question.id, button, question.answers[answerIndex]);
            });
        });

        startTimer();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function startTimer() {
        clearInterval(timerHandle);
        timeLeft = TIME_PER_QUESTION;
        updateTimerDisplay();

        timerHandle = setInterval(function () {
            timeLeft--;
            updateTimerDisplay();

            if (timeLeft <= 0) {
                clearInterval(timerHandle);
                if (!locked) {
                    revealCorrectAnswer();
                    setTimeout(goToNext, 1200);
                }
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        timerNumber.textContent = Math.max(timeLeft, 0) + 's';

        const percent = Math.max((timeLeft / TIME_PER_QUESTION) * 100, 0);
        timerBar.style.width = percent + '%';

        timerBar.classList.remove('is-warning', 'is-danger');
        if (timeLeft <= 5) {
            timerBar.classList.add('is-danger');
        } else if (timeLeft <= 10) {
            timerBar.classList.add('is-warning');
        }
    }

    function selectAnswer(questionId, button, answer) {
        if (locked) {
            return;
        }

        answers[questionId] = button.dataset.answerId;
        locked = true;

        disableAllButtons();

        if (answer.is_correct) {
            button.classList.add('is-correct');
        } else {
            button.classList.add('is-incorrect');
            highlightCorrectButton();
        }

        clearInterval(timerHandle);
        setTimeout(goToNext, 1200);
    }

    function revealCorrectAnswer() {
        locked = true;
        disableAllButtons();
        highlightCorrectButton();
    }

    function disableAllButtons() {
        questionContainer.querySelectorAll('.quiz-answer-btn').forEach(function (button) {
            button.disabled = true;
        });
    }

    function highlightCorrectButton() {
        questionContainer.querySelectorAll('.quiz-answer-btn').forEach(function (button) {
            if (button.dataset.correct === '1') {
                button.classList.add('is-correct');
            }
        });
    }

    function goToNext() {
        if (currentIndex < questions.length - 1) {
            currentIndex++;
            renderQuestion();
        } else {
            finishQuiz();
        }
    }

    function finishQuiz() {
        progressBar.style.width = '100%';
        progressLabel.textContent = 'Question ' + questions.length + ' / ' + questions.length;

        questionContainer.innerHTML = '<div class="text-center quiz-spinner-text py-5">'
            + '<div class="spinner-border mb-3" style="color:#c9a227;" role="status"></div>'
            + '<p>CALCUL DU SCORE EN COURS...</p>'
            + '</div>';

        let inputsHtml = '';
        Object.keys(answers).forEach(function (questionId) {
            inputsHtml += '<input type="hidden" name="answers[' + questionId + ']" value="' + answers[questionId] + '">';
        });
        answersInputs.innerHTML = inputsHtml;

        submitForm.submit();
    }

    if (questions.length > 0) {
        renderQuestion();
    }
});
</script>

@endsection
