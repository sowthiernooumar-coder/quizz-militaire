@extends('layouts.dashboard')

@section('content')

<h2>

Détail du Quiz

</h2>

<div class="alert alert-info">

Score :

{{ $session->score_percentage }} %

</div>

@foreach($session->answers as $answer)

<div class="card mb-3">

<div class="card-body">

<h5>

{{ $answer->question->question }}

</h5>

<p>

Réponse choisie :

{{ $answer->selectedAnswer->answer_text }}

</p>

@if($answer->is_correct)

<span
class="badge bg-success">

Bonne réponse

</span>

@else

<span
class="badge bg-danger">

Mauvaise réponse

</span>

@endif

</div>

</div>

@endforeach

@endsection