@extends('layouts.dashboard')

@section('content')

<h2>

Gestion des Matières

</h2>

<a href="{{ route('admin.subjects.create') }}"
   class="btn btn-primary mb-3">

    Nouvelle Matière

</a>

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<div class="table-responsive">
<table class="table table-bordered">

    <thead>

        <tr>

            <th>ID</th>

            <th>Nom</th>

            <th>Description</th>

            <th>Actions</th>

        </tr>

    </thead>

    <tbody>

        @foreach($subjects as $subject)

        <tr>

            <td>
                {{ $subject->id }}
            </td>

            <td>
                {{ $subject->name }}
            </td>

            <td>
                {{ $subject->description }}
            </td>

            <td>

                <a href="{{ route(
                    'admin.subjects.edit',
                    $subject
                ) }}"
                class="btn btn-warning">

                    Modifier

                </a>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.subjects.destroy',
                        $subject
                    ) }}"
                    class="d-inline">

                    @csrf

                    @method('DELETE')

                    <button
                        class="btn btn-danger">

                        Supprimer

                    </button>

                </form>

            </td>

        </tr>

        @endforeach

    </tbody>

</table>
</div>

{{ $subjects->links() }}

@endsection