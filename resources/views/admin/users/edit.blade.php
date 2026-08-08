@extends('layouts.dashboard')

@section('content')

<h2>

Modifier Utilisateur

</h2>

<p class="text-muted">

Rôle : <strong>{{ $user->getRoleNames()->implode(', ') }}</strong>

</p>

<form method="POST"

action="{{ route(
'admin.users.update',
$user
) }}"

enctype="multipart/form-data">

@csrf

@method('PUT')

<div class="mb-3">

<label>

Photo de profil

</label>

@if($user->profile?->avatar)
    <div class="mb-2">
        <img
            src="{{ asset('storage/' . $user->profile->avatar) }}"
            alt="Photo actuelle"
            class="rounded-circle"
            style="width: 80px; height: 80px; object-fit: cover;"
        >
    </div>
@endif

<input
type="file"
name="avatar"
accept="image/*"
class="form-control">

</div>

<div class="mb-3">

<label>

Prénom

</label>

<input
type="text"
name="first_name"
value="{{ $user->first_name }}"
class="form-control">

</div>

<div class="mb-3">

<label>

Nom

</label>

<input
type="text"
name="last_name"
value="{{ $user->last_name }}"
class="form-control">

</div>

<div class="mb-3">

<label>

Email

</label>

<input
type="email"
name="email"
value="{{ $user->email }}"
class="form-control">

</div>

<div class="mb-3">

<label>

Promotion

</label>

<select
name="promotion_id"
class="form-control">

<option value="">— Aucune —</option>

@foreach(
$promotions as $promotion
)

<option

value="{{ $promotion->id }}"

@if(
$user->promotion_id
==
$promotion->id
)

selected

@endif

>

{{ $promotion->name }}

</option>

@endforeach

</select>

</div>

<button
class="btn btn-success">

Enregistrer

</button>

</form>

@endsection