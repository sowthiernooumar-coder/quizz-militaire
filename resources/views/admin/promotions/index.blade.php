@extends('layouts.dashboard')

@section('content')

<h2>

Gestion des Promotions

</h2>

<a href="{{ route('admin.promotions.create') }}"
   class="btn btn-primary mb-3">

    Nouvelle Promotion

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

            <th>Début</th>

            <th>Fin</th>

            <th>Actions</th>

        </tr>

    </thead>

    <tbody>

        @foreach($promotions as $promotion)

        <tr>

            <td>
                {{ $promotion->id }}
            </td>

            <td>
                {{ $promotion->name }}
            </td>

            <td>
                {{ $promotion->description }}
            </td>

            <td>
                {{ $promotion->start_date }}
            </td>

            <td>
                {{ $promotion->end_date }}
            </td>

            <td>

                <a href="{{ route(
                    'admin.promotions.edit',
                    $promotion
                ) }}"
                class="btn btn-warning">

                    Modifier

                </a>

                <form
                    method="POST"
                    action="{{ route(
                        'admin.promotions.destroy',
                        $promotion
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

{{ $promotions->links() }}

@endsection
