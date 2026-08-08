@extends('layouts.dashboard')

@section('content')

<a href="{{ route('student.dashboard') }}" class="btn btn-link mb-3">
    &larr; Retour
</a>

<h2>

Configurer un Quiz

</h2>

<form method="POST" action="{{ route('student.quiz.start') }}">

@csrf

<div class="mb-3">

<label>

Matière

</label>

<select
name="subject_id"
class="form-control">

@foreach($subjects as $subject)

<option value="{{ $subject->id }}">

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

<option value="{{ $level }}">{{ $level }}</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>

Nombre de questions

</label>

<input
type="number"
name="number_of_questions"
value="10"
class="form-control">

</div>

<button
class="btn btn-success">

Commencer

</button>

</form>

@endsection