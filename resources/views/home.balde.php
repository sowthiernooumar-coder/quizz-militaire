@extends('layouts.app')

@section('title','Accueil')

@section('content')

<div class="text-center">

    <h1 class="display-4">

        Plateforme Militaire de Quiz

    </h1>

    <p class="lead">

        Système d'évaluation des
        stagiaires soldats

    </p>

    <a href="{{ route('login') }}"
       class="btn btn-primary">

        Connexion

    </a>

    <a href="{{ route('register') }}"
       class="btn btn-success">

        Inscription

    </a>

</div>

@endsection