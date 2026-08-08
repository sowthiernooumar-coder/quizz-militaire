@extends('layouts.dashboard')

@section('content')

<h2>

Attribution des matières — Instructeurs

</h2>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

@forelse($instructors as $instructor)

<div class="card mb-3">

    <div class="card-body">

        <h5 class="card-title">

            {{ $instructor->first_name }} {{ $instructor->last_name }}

            <span class="badge bg-secondary">
                {{ $instructor->getRoleNames()->implode(', ') }}
            </span>

        </h5>

        <p class="card-subtitle text-muted mb-3">

            {{ $instructor->email }}
            — Promotion : {{ $instructor->promotion?->name ?? '—' }}

        </p>

        @include('instructor.management._subject-form', [
            'target' => $instructor,
            'subjects' => $subjects,
            'action' => route('admin.instructors.update', $instructor),
        ])

    </div>

</div>

@empty

<p>

Aucun instructeur trouvé.

</p>

@endforelse

@endsection
