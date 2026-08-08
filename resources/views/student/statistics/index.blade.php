@extends('layouts.dashboard')

@section('content')

<h2>

Mes Statistiques

</h2>

<div class="row">

<div class="col-12 col-md-4">

<div class="card">

<div class="card-body">

<h5>

Quiz passés

</h5>

<h2>

{{ $totalQuiz }}

</h2>

</div>

</div>

</div>

<div class="col-12 col-md-4">

<div class="card">

<div class="card-body">

<h5>

Moyenne

</h5>

<h2>

{{ $averageScore }} %

</h2>

</div>

</div>

</div>

<div class="col-12 col-md-4">

<div class="card">

<div class="card-body">

<h5>

Meilleur score

</h5>

<h2>

{{ $bestScore }} %

</h2>

</div>

</div>

</div>

</div>

@endsection