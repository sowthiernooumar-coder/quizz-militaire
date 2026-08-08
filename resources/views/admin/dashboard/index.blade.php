@extends('layouts.dashboard')

@section('content')

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<h1>

Dashboard Administrateur

</h1>

<div class="row">

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Utilisateurs

</h5>

<h2>

{{ $totalUsers }}

</h2>

</div>

</div>

</div>

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Stagiaires

</h5>

<h2>

{{ $totalStudents }}

</h2>

</div>

</div>

</div>

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Instructeurs L1

</h5>

<h2>

{{ $totalLevel1 }}

</h2>

</div>

</div>

</div>

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Instructeurs L2

</h5>

<h2>

{{ $totalLevel2 }}

</h2>

</div>

</div>

</div>

</div>

<hr>

<div class="row">

<div class="col-12 col-md-4">

<div class="card">

<div class="card-body">

<h5>

Quiz

</h5>

<h2>

{{ $totalQuizzes }}

</h2>

</div>

</div>

</div>

<div class="col-12 col-md-4">

<div class="card">

<div class="card-body">

<h5>

Questions

</h5>

<h2>

{{ $totalQuestions }}

</h2>

</div>

</div>

</div>

<div class="col-12 col-md-4">

<div class="card">

<div class="card-body">

<h5>

Sessions Quiz

</h5>

<h2>

{{ $totalSessions }}

</h2>

</div>

</div>

</div>

</div>

<hr>

<h3>Dernières connexions</h3>

@php
    $loginGroups = [
        'instructor_l2' => ['label' => 'Instructeurs Niveau 2', 'color' => 'warning'],
        'instructor_l1' => ['label' => 'Instructeurs Niveau 1', 'color' => 'info'],
        'student'       => ['label' => 'Stagiaires',            'color' => 'success'],
    ];
@endphp

<div class="row g-3 mb-3">

    @foreach($loginGroups as $role => $meta)
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-{{ $meta['color'] }} me-1">{{ $recentLogins[$role]->count() }}</span>
                <strong>{{ $meta['label'] }}</strong>
            </div>
            <div class="card-body">
                @if($recentLogins[$role]->isEmpty())
                    <p class="text-muted mb-0 small">Aucune connexion récente.</p>
                @else
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($recentLogins[$role] as $login)
                            <div
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                data-bs-html="true"
                                data-bs-title="<strong>{{ $login->user->first_name }} {{ $login->user->last_name }}</strong><br>{{ $login->login_at?->format('d/m/Y H:i') }}"
                            >
                                @include('partials.user-avatar', [
                                    'user'   => $login->user,
                                    'size'   => 52,
                                    'online' => $onlineUserIds->contains($login->user_id)
                                ])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>

<hr>

<h3>Activités Récentes</h3>

@php
    $activityGroups = [
        'instructor_l2' => ['label' => 'Instructeurs Niveau 2', 'color' => 'warning'],
        'instructor_l1' => ['label' => 'Instructeurs Niveau 1', 'color' => 'info'],
        'student'       => ['label' => 'Stagiaires',            'color' => 'success'],
    ];
@endphp

<div class="row g-3 mb-3">

    @foreach($activityGroups as $role => $meta)
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-{{ $meta['color'] }} me-1">{{ $recentActivities[$role]->count() }}</span>
                <strong>{{ $meta['label'] }}</strong>
            </div>
            <div class="card-body p-0">
                @if($recentActivities[$role]->isEmpty())
                    <p class="text-muted mb-0 small p-3">Aucune activité récente.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivities[$role] as $activity)
                            <li class="list-group-item d-flex align-items-center gap-3 py-2 px-3">
                                @include('partials.user-avatar', [
                                    'user'   => $activity->user,
                                    'size'   => 38,
                                    'online' => $onlineUserIds->contains($activity->user_id)
                                ])
                                <div>
                                    <span class="fw-semibold">{{ $activity->user->first_name }} {{ $activity->user->last_name }}</span>
                                    <span class="text-muted ms-1">a visité</span>
                                    <code class="ms-1">{{ $activity->description }}</code>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @endforeach

</div>

<hr>

<h3>

Score Moyen et Meilleur Score par Promotion

</h3>

<div class="row mt-2">

    @forelse($promotionScores as $promotionScore)

        <div class="col-md-4 mb-3">

            <div class="card">

                <div class="card-body">

                    <h5>{{ $promotionScore['name'] }}</h5>

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">Score moyen</small>
                            <h3 class="mb-0">{{ $promotionScore['average'] }} %</h3>
                        </div>

                        <div>
                            <small class="text-muted">Meilleur score</small>
                            <h3 class="mb-0">{{ $promotionScore['best'] }} %</h3>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <p class="text-muted">Aucune promotion enregistrée.</p>

    @endforelse

</div>

<hr>

<h3>

Comparatif Journalier des Promotions

</h3>

<canvas id="promotionDailyChart" height="100"></canvas>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const chartData = @json($promotionDailyChart);

        new Chart(document.getElementById('promotionDailyChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets,
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: { display: true, text: 'Score moyen (%)' },
                    },
                },
            },
        });
    });
</script>

@endsection