@extends('layouts.dashboard')

@section('content')

<h2>

Journal des Connexions

</h2>

<div class="table-responsive">
<table class="table">

<thead>

<tr>

<th>Utilisateur</th>

<th>Connexion</th>

</tr>

</thead>

<tbody>

@foreach($logins as $log)

<tr>

<td>

{{ $log->user->first_name }}

</td>

<td>

{{ $log->login_at }}

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

<hr>

<h2>

Journal des Activités

</h2>

<div class="table-responsive">
<table class="table">

<thead>

<tr>

<th>Utilisateur</th>

<th>Action</th>

<th>Description</th>

</tr>

</thead>

<tbody>

@foreach($activities as $activity)

<tr>

<td>

{{ $activity->user->first_name }}

</td>

<td>

{{ $activity->action }}

</td>

<td>

{{ $activity->description }}

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

@endsection