@extends('layouts.dashboard')

@section('content')

<h2>

Ajouter une Matière

</h2>

<form method="POST"
      action="{{ route(
        'admin.subjects.store'
      ) }}">

    @csrf

    <div class="mb-3">

        <label>

            Nom

        </label>

        <input
            type="text"
            name="name"
            class="form-control">

    </div>

    <div class="mb-3">

        <label>

            Description

        </label>

        <textarea
            name="description"
            class="form-control">
        </textarea>

    </div>

    <button
        class="btn btn-success">

        Enregistrer

    </button>

</form>

@endsection