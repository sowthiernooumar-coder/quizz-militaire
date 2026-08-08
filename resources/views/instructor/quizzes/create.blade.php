@extends('layouts.dashboard')

@section('content')

<a href="{{ route('instructor.quizzes.index') }}" class="btn btn-link mb-3">
    &larr; Retour
</a>

<h2>

Créer Quiz

</h2>

@if($errors->any())

<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

@endif

<form method="POST"

action="{{ route(
'instructor.quizzes.store'
) }}"

enctype="multipart/form-data">

@csrf

<div class="mb-3">

<label>

Titre

</label>

<input
type="text"
name="title"
value="{{ old('title') }}"
class="form-control">

</div>

<div class="mb-3">

<label>

Matière

</label>

<select
name="subject_id"
class="form-control">

@foreach($subjects as $subject)

<option
value="{{ $subject->id }}"
@selected(old('subject_id') == $subject->id)>

{{ $subject->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>

Niveau

</label>

<select
name="difficulty_level"
class="form-control">

@foreach($levels as $level)

<option value="{{ $level }}" @selected(old('difficulty_level') === $level)>
{{ $level }}
</option>

@endforeach

</select>

</div>

<hr>

<div class="mb-3">

<label>

Question

</label>

<textarea
name="question"
class="form-control">{{ old('question') }}</textarea>

</div>

<div class="mb-3">

<label>

Explication <span class="text-muted">(facultatif)</span>

</label>

<textarea
name="explanation"
class="form-control">{{ old('explanation') }}</textarea>

</div>

<div class="mb-3">

<label>

Image de la question <span class="text-muted">(facultatif)</span>

</label>

<input
type="file"
name="image"
accept="image/*"
class="form-control">

</div>

<label class="form-label">

Réponses (sélectionnez la bonne réponse)

</label>

@for($i = 0; $i < 4; $i++)

<div class="mb-2 d-flex align-items-center gap-2">

    <input
        type="radio"
        name="correct_answer"
        value="{{ $i }}"
        @checked(old('correct_answer') == $i)
        required
    >

    <input
        type="text"
        name="answers[]"
        value="{{ old('answers.' . $i) }}"
        placeholder="Réponse {{ $i + 1 }}"
        class="form-control"
    >

</div>

@endfor

<button
class="btn btn-success mt-3">

Créer Quiz

</button>

</form>

@endsection
