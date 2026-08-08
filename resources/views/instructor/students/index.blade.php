@extends('layouts.dashboard')

@section('content')

<a href="{{ route('instructor.dashboard') }}" class="btn btn-link mb-3 px-0">
    &larr; Retour
</a>

<h2>

Liste des Stagiaires

</h2>

<div class="table-responsive">
<table class="table table-bordered">

<thead>

<tr>

<th>Nom</th>

<th>Email</th>

<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($students as $student)

<tr>

<td>

{{ $student->first_name }}
{{ $student->last_name }}

</td>

<td>

{{ $student->email }}

</td>

<td>

<a
href="{{ route('instructor.students.sessions', $student) }}"
class="btn btn-primary">

Sessions de quiz

</a>

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

{{ $students->links() }}

@endsection
