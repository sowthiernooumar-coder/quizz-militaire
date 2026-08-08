@extends('layouts.dashboard')

@section('content')

<a href="{{ route('student.dashboard') }}" class="btn btn-link mb-3 px-0">
    &larr; Retour
</a>

<h2>

Résultat du Quiz

</h2>

<div class="alert alert-info">

Score :

{{ $session->correct_answers }}

/

{{ $session->total_questions }}

<br>

Pourcentage :

{{ number_format($session->score_percentage, 0) }} %

</div>

@foreach($questions as $index => $question)

    @php
        $sessionAnswer = $answersByQuestion->get($question->id);
        $correctAnswer = $question->answers->firstWhere('is_correct', true);
    @endphp

    <div class="card mb-3">

        <div class="card-body">

            <h5>

                {{ $index + 1 }}. {{ $question->question }}

            </h5>

            @if($question->image)

                <div class="text-center mb-3">
                    <img
                        src="{{ asset('storage/' . $question->image) }}"
                        alt=""
                        class="img-fluid rounded"
                        style="max-height: 320px;"
                    >
                </div>

            @endif

            @if($sessionAnswer)

                <p>

                    Votre réponse :

                    {{ $sessionAnswer->selectedAnswer->answer_text }}

                </p>

                @if($sessionAnswer->is_correct)

                    <span class="badge bg-success">

                        Correct

                    </span>

                @else

                    <span class="badge bg-danger">

                        Incorrect

                    </span>

                    <p class="mt-2 mb-0">

                        Bonne réponse :

                        {{ $correctAnswer?->answer_text }}

                    </p>

                @endif

            @else

                <span class="badge bg-warning text-dark">

                    Temps écoulé — non répondu

                </span>

                <p class="mt-2 mb-0">

                    Bonne réponse :

                    {{ $correctAnswer?->answer_text }}

                </p>

            @endif

            @if($question->explanation)

                <p class="mt-2 mb-0 text-muted">

                    {{ $question->explanation }}

                </p>

            @endif

        </div>

    </div>

@endforeach

@endsection
