@extends('layouts.dashboard')

@section('content')

<h2>

Modifier Matière

</h2>

<form method="POST"
      action="{{ route(
          'admin.subjects.update',
          $subject
      ) }}">

    @csrf

    @method('PUT')

    <div class="mb-3">

        <label>

            Nom

        </label>

        <input
            type="text"
            name="name"
            value="{{ $subject->name }}"
            class="form-control">

    </div>

    <div class="mb-3">

        <label>

            Description

        </label>

        <textarea
            name="description"
            class="form-control">{{ $subject->description }}</textarea>

    </div>

    <button
        class="btn btn-primary">

        Modifier

    </button>

</form>

@endsection