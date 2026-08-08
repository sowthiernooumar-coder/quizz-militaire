@extends('layouts.dashboard')

@section('content')

<a href="{{ route('instructor.students.index') }}" class="btn btn-link mb-3 px-0">
    &larr; Retour
</a>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

<h2>

Sessions de quiz — {{ $student->first_name }} {{ $student->last_name }}

</h2>

<div class="table-responsive">
<table class="table table-bordered">

    <thead>
        <tr>
            <th>Quiz</th>
            <th>Score</th>
            <th>Statut</th>
            <th>Démarré le</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        @forelse($sessions as $session)

            <tr>

                <td>{{ $session->quiz->title }}</td>

                <td>
                    {{ $session->correct_answers }} / {{ $session->total_questions }}
                    ({{ number_format($session->score_percentage, 0) }} %)
                </td>

                <td>
                    @if($session->finished_at)
                        <span class="badge bg-success">Terminé</span>
                    @else
                        <span class="badge bg-warning text-dark">En cours</span>
                    @endif
                </td>

                <td>{{ $session->started_at?->format('d/m/Y H:i') }}</td>

                <td>

                    <form
                        method="POST"
                        action="{{ route('instructor.students.sessions.reset', [$student, $session]) }}"
                        onsubmit="return confirm('Réinitialiser cette session ? Le stagiaire pourra repasser le quiz.');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Réinitialiser
                        </button>
                    </form>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="text-center text-muted">
                    Aucune session de quiz pour ce stagiaire.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>
</div>

{{ $sessions->links() }}

@endsection
