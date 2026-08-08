@extends('layouts.dashboard')

@section('content')

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<h1>

Bienvenue, {{ auth()->user()->first_name }}

</h1>

<p class="text-muted">

Voici un aperçu de vos performances.

</p>

<div class="row mt-4">

    <div class="col-6 col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Quiz passés</h6>

                <h2>{{ $quizzesTaken }}</h2>

            </div>

        </div>

    </div>

    <div class="col-6 col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Score moyen</h6>

                <h2>{{ $averageScore }} %</h2>

            </div>

        </div>

    </div>

    <div class="col-6 col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Meilleur score</h6>

                <h2>{{ $bestScore }} %</h2>

            </div>

        </div>

    </div>

    <div class="col-6 col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Dernier score</h6>

                <h2>
                    {{ $lastSession?->score_percentage ?? '—' }}
                    @if($lastSession) % @endif
                </h2>

            </div>

        </div>

    </div>

</div>

<div class="mt-4">

    <a href="{{ route('student.quiz.configuration') }}" class="btn btn-primary">
        Passer un quiz
    </a>

    <a href="{{ route('student.history.index') }}" class="btn btn-outline-secondary">
        Mon historique
    </a>

    <a href="{{ route('student.statistics.index') }}" class="btn btn-outline-secondary">
        Mes statistiques
    </a>

</div>

@endsection
