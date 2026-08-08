@extends('layouts.dashboard')

@section('content')

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

<h1>

Gestion des utilisateurs

</h1>

<div class="table-responsive">
<table class="table table-bordered">

<thead>

<tr>

<th>Nom</th>

<th>Email</th>

<th>Rôle</th>

<th>Promotion</th>

<th>Statut</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>

{{ $user->first_name }}
{{ $user->last_name }}

</td>

<td>

{{ $user->email }}

</td>

<td>

{{ $user->getRoleNames()->implode(', ') }}

</td>

<td>

{{ $user->promotion?->name ?? '—' }}

</td>

<td>

@if($user->is_active)

<span class="badge bg-success">

Actif

</span>

@else

<span class="badge bg-danger">

Inactif

</span>

@endif

</td>

<td>

<a
href="{{ route(
'admin.users.show',
$user
) }}"
class="btn btn-primary btn-sm">

Profil

</a>

<a
href="{{ route(
'admin.users.edit',
$user
) }}"
class="btn btn-secondary btn-sm">

Modifier

</a>

@if($user->id !== auth()->id())

<form
method="POST"
action="{{ route('admin.users.destroy', $user) }}"
class="d-inline"
onsubmit="return confirm('Supprimer définitivement {{ $user->first_name }} {{ $user->last_name }} ? Cette action est irréversible.');"
>
@csrf
@method('DELETE')

<button type="submit" class="btn btn-danger btn-sm">
Supprimer
</button>

</form>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

{{ $users->links() }}

@endsection