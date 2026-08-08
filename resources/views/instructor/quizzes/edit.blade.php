@extends('layouts.dashboard')

@section('content')

<a href="{{ route('instructor.quizzes.index') }}" class="btn btn-link mb-3">
    &larr; Retour
</a>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

@if($errors->any())

<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

@endif

<h2>

Modifier Quiz

</h2>

<!-- Informations générales du quiz -->
<div class="card mb-4">
    <div class="card-body">

        <form method="POST" action="{{ route('instructor.quizzes.update', $quiz) }}">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Titre</label>
                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Matière</label>
                <select name="subject_id" class="form-control">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id', $quiz->subject_id) == $subject->id)>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Niveau</label>
                <select name="difficulty_level" class="form-control">
                    @foreach($levels as $level)
                        <option value="{{ $level }}" @selected(old('difficulty_level', $quiz->difficulty_level) === $level)>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Enregistrer les informations</button>

        </form>

    </div>
</div>

<!-- Questions existantes -->
<h4>Questions ({{ $questions->count() }})</h4>

@foreach($questions as $question)

<div class="card mb-3">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">

            <h6 class="mb-3">Question {{ $loop->iteration }}</h6>

            <form method="POST" action="{{ route('instructor.quizzes.questions.destroy', [$quiz, $question]) }}" onsubmit="return confirm('Supprimer cette question ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
            </form>

        </div>

        <form method="POST" action="{{ route('instructor.quizzes.questions.update', [$quiz, $question]) }}" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Question</label>
                <textarea name="question" class="form-control">{{ $question->question }}</textarea>
            </div>

            <div class="mb-3">
                <label>Explication <span class="text-muted">(facultatif)</span></label>
                <textarea name="explanation" class="form-control">{{ $question->explanation }}</textarea>
            </div>

            <div class="mb-3">

                <label>Image de la question <span class="text-muted">(facultatif)</span></label>

                @if($question->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $question->image) }}" alt="Image de la question" style="max-width: 220px;" class="img-thumbnail">
                    </div>
                @endif

                <input type="file" name="image" accept="image/*" class="form-control">

            </div>

            <label class="form-label">Réponses (sélectionnez la bonne réponse)</label>

            @php
                $sortedAnswers = $question->answers->sortBy('display_order')->values();
            @endphp

            @for($i = 0; $i < 4; $i++)

                <div class="mb-2 d-flex align-items-center gap-2">

                    <input
                        type="radio"
                        name="correct_answer"
                        value="{{ $i }}"
                        @checked($sortedAnswers->get($i)?->is_correct)
                        required
                    >

                    <input
                        type="text"
                        name="answers[]"
                        value="{{ $sortedAnswers->get($i)?->answer_text }}"
                        placeholder="Réponse {{ $i + 1 }}"
                        class="form-control"
                    >

                </div>

            @endfor

            <button class="btn btn-warning mt-2">Mettre à jour cette question</button>

        </form>

    </div>
</div>

@endforeach

<!-- Ajouter une nouvelle question -->
<div class="card mb-4 border-success">
    <div class="card-body">

        <h6 class="mb-3">Ajouter une question</h6>

        <form method="POST" action="{{ route('instructor.quizzes.questions.store', $quiz) }}" enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label>Question</label>
                <textarea name="question" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Explication <span class="text-muted">(facultatif)</span></label>
                <textarea name="explanation" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Image de la question <span class="text-muted">(facultatif)</span></label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>

            <label class="form-label">Réponses (sélectionnez la bonne réponse)</label>

            @for($i = 0; $i < 4; $i++)

                <div class="mb-2 d-flex align-items-center gap-2">

                    <input type="radio" name="correct_answer" value="{{ $i }}" required>

                    <input
                        type="text"
                        name="answers[]"
                        placeholder="Réponse {{ $i + 1 }}"
                        class="form-control"
                    >

                </div>

            @endfor

            <button class="btn btn-success mt-2">Ajouter la question</button>

        </form>

    </div>
</div>

@endsection
