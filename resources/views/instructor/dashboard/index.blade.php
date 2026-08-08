@extends('layouts.dashboard')

@section('content')

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<h1>

Dashboard Instructeur

</h1>

<div class="row">

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Stagiaires

</h5>

<h2>

{{ $students }}

</h2>

</div>

</div>

</div>

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Mes Quiz

</h5>

<h2>

{{ $quizCount }}

</h2>

</div>

</div>

</div>

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Sessions Quiz

</h5>

<h2>

{{ $quizSessions }}

</h2>

</div>

</div>

</div>

<div class="col-6 col-md-3">

<div class="card">

<div class="card-body">

<h5>

Score Moyen

</h5>

<h2>

{{ $averageScore }} %

</h2>

</div>

</div>

</div>

</div>

@php
    $loginGroupsMeta = [
        'instructor_l1' => ['label' => 'Instructeurs Niveau 1', 'color' => 'info'],
        'student'       => ['label' => 'Stagiaires',            'color' => 'success'],
    ];
@endphp

<hr>

<h3>Dernières connexions
    <small class="text-muted fs-6 fw-normal ms-2">— de votre promotion</small>
</h3>

<div class="row g-3 mb-3">
    @foreach($recentLogins as $role => $logins)
    @php $meta = $loginGroupsMeta[$role] ?? ['label' => $role, 'color' => 'secondary']; @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-{{ $meta['color'] }} me-1">{{ $logins->count() }}</span>
                <strong>{{ $meta['label'] }}</strong>
            </div>
            <div class="card-body">
                @if($logins->isEmpty())
                    <p class="text-muted mb-0 small">Aucune connexion récente.</p>
                @else
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($logins as $login)
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

<hr>

<h3>Activités Récentes
    <small class="text-muted fs-6 fw-normal ms-2">— de votre promotion</small>
</h3>

<div class="row g-3 mb-3">
    @foreach($recentActivities as $role => $activities)
    @php $meta = $loginGroupsMeta[$role] ?? ['label' => $role, 'color' => 'secondary']; @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-{{ $meta['color'] }} me-1">{{ $activities->count() }}</span>
                <strong>{{ $meta['label'] }}</strong>
            </div>
            <div class="card-body p-0">
                @if($activities->isEmpty())
                    <p class="text-muted mb-0 small p-3">Aucune activité récente.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($activities as $activity)
                            <li class="list-group-item d-flex align-items-center gap-3 py-2 px-3">
                                @include('partials.user-avatar', [
                                    'user'   => $activity->user,
                                    'size'   => 38,
                                    'online' => $onlineUserIds->contains($activity->user_id)
                                ])
                                <div>
                                    <span class="fw-semibold">{{ $activity->user->first_name }} {{ $activity->user->last_name }}</span>
                                    <span class="text-muted ms-1">a visité</span>
                                    <code class="ms-1">{{ $activity->description ?? $activity->action }}</code>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>

@if($studentDailyChart && count($studentDailyChart['datasets']))

<hr>

<h3>Comparatif Journalier des Stagiaires
    <small class="text-muted fs-6 fw-normal ms-2">— 14 derniers jours · votre promotion</small>
</h3>

<div class="card mt-2">
    <div class="card-body">
        <canvas id="studentDailyChart" height="100"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @php
            $chartJson = json_encode($studentDailyChart);
        @endphp
        const studentChartData = {!! $chartJson !!};

        new Chart(document.getElementById('studentDailyChart'), {
            type: 'line',
            data: {
                labels: studentChartData.labels,
                datasets: studentChartData.datasets,
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#cbd5e1', padding: 16, boxWidth: 14 },
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label} : ${ctx.parsed.y} %`,
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid:  { color: 'rgba(255,255,255,0.06)' },
                    },
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { color: '#94a3b8', callback: v => v + ' %' },
                        grid:  { color: 'rgba(255,255,255,0.06)' },
                    },
                },
            },
        });
    });
</script>

@endif

@endsection