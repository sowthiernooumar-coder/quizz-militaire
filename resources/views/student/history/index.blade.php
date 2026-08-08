@extends('layouts.dashboard')

@section('content')

<h2>

Historique des Quiz

</h2>

<div class="table-responsive">
<table class="table table-bordered">

<thead>

<tr>

<th>Date</th>

<th>Matière</th>

<th>Score</th>

<th>Détails</th>

</tr>

</thead>

<tbody>

@foreach($sessions as $session)

<tr>

<td>

{{ $session->created_at->format('d/m/Y H:i') }}

</td>

<td>

{{ $session->quiz->subject->name }}

</td>

<td>

{{ $session->score_percentage }} %

</td>

<td>

<a

href="{{ route(
'student.history.show',
$session
) }}"

class="btn btn-primary">

Voir

</a>

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

{{ $sessions->links() }}

@endsection