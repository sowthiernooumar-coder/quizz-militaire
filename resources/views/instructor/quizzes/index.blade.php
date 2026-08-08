@extends('layouts.dashboard')

@section('content')

<a href="{{ route('instructor.dashboard') }}" class="btn btn-link mb-3">
    &larr; Retour
</a>

<h2>

Mes Quiz

</h2>

<a href="{{ route(
'instructor.quizzes.create'
) }}"
class="btn btn-primary mb-3">

Nouveau Quiz

</a>

<div class="table-responsive">
<table class="table table-bordered">

<thead>

<tr>

<th>Titre</th>

<th>Matière</th>

<th>Niveau</th>

<th>Questions</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

@foreach($quizzes as $quiz)

<tr>

<td>

{{ $quiz->title }}

</td>

<td>

{{ $quiz->subject->name }}

</td>

<td>

{{ $quiz->difficulty_level }}

</td>

<td>

{{ $quiz->questions->count() }}

</td>

<td>

<a

href="{{ route(
'instructor.quizzes.edit',
$quiz
) }}"

class="btn btn-warning">

Modifier

</a>

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

@endsection