@extends('layouts.dashboard')

@section('content')

<h2>

Ajouter une Promotion

</h2>

<form method="POST"
      action="{{ route(
        'admin.promotions.store'
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

    <div class="mb-3">

        <label>

            Date de début

        </label>

        <input
            type="date"
            name="start_date"
            class="form-control">

    </div>

    <div class="mb-3">

        <label>

            Date de fin

        </label>

        <input
            type="date"
            name="end_date"
            class="form-control">

    </div>

    <button
        class="btn btn-success">

        Enregistrer

    </button>

</form>

@endsection
