@extends('layouts.dashboard')

@section('content')

<h1>

Profil Utilisateur

</h1>

<p>

Nom :

{{ $user->first_name }}
{{ $user->last_name }}

</p>

<p>

Email :

{{ $user->email }}

</p>

<p>

Rôle :

{{ $user->getRoleNames()->implode(', ') }}

</p>

<p>

Promotion :

{{ $user->promotion?->name }}

</p>

<hr>

<form
method="POST"
action="{{ route(
'admin.users.activate',
$user
) }}">

@csrf

<button
class="btn btn-success">

Activer

</button>

</form>

<br>

<form
method="POST"
action="{{ route(
'admin.users.deactivate',
$user
) }}">

@csrf

<button
class="btn btn-danger">

Désactiver

</button>

</form>

<br>

<form
method="POST"
action="{{ route(
'admin.users.reset-password',
$user
) }}">

@csrf

<button
class="btn btn-warning">

Réinitialiser mot de passe

</button>

</form>

@endsection