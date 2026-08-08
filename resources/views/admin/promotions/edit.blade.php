@extends('layouts.dashboard')

@section('content')

<h2>

Modifier Promotion

</h2>

<form method="POST"
      action="{{ route(
          'admin.promotions.update',
          $promotion
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
            value="{{ $promotion->name }}"
            class="form-control">

    </div>

    <div class="mb-3">

        <label>

            Description

        </label>

        <textarea
            name="description"
            class="form-control">{{ $promotion->description }}</textarea>

    </div>

    <div class="mb-3">

        <label>

            Date de début

        </label>

        <input
            type="date"
            name="start_date"
            value="{{ $promotion->start_date }}"
            class="form-control">

    </div>

    <div class="mb-3">

        <label>

            Date de fin

        </label>

        <input
            type="date"
            name="end_date"
            value="{{ $promotion->end_date }}"
            class="form-control">

    </div>

    <button
        class="btn btn-primary">

        Modifier

    </button>

</form>

@endsection
