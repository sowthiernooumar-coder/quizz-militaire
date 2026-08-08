@extends('layouts.dashboard')

@section('content')

<h2>

Attribution des matières

</h2>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<h4>

Mes matières

</h4>

<div class="card mb-4">

    <div class="card-body">

        <h5 class="card-title">

            {{ $self->first_name }} {{ $self->last_name }} (vous)

        </h5>

        @include('instructor.management._subject-form', ['target' => $self, 'subjects' => $subjects])

    </div>

</div>

<h4>

Instructeurs L1 de votre promotion

</h4>

@forelse($instructors as $instructor)

<div class="card mb-3">

    <div class="card-body">

        <h5 class="card-title">

            {{ $instructor->first_name }} {{ $instructor->last_name }}

        </h5>

        <p class="card-subtitle text-muted mb-3">

            {{ $instructor->email }}

        </p>

        @include('instructor.management._subject-form', ['target' => $instructor, 'subjects' => $subjects])

    </div>

</div>

@empty

<p>

Aucun instructeur L1 trouvé dans votre promotion.

</p>

@endforelse

@endsection
