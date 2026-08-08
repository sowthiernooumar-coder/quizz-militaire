<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Quizz Militaire') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased d-flex flex-column min-vh-100 bg-light">

        <!-- Header -->
        <header class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
            <div class="container-fluid">

                <a class="navbar-brand fs-3 fw-semibold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    <span class="fs-2">🎖️</span> {{ config('app.name', 'Quizz Militaire') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">

                    <ul class="navbar-nav me-auto align-items-lg-center gap-lg-2">

                        @auth

                            @role('admin')
                                <li class="nav-item">
                                    <a class="nav-link fs-5 px-3" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                            @endrole

                            @role('instructor_l1|instructor_l2')
                                <li class="nav-item">
                                    <a class="nav-link fs-5 px-3" href="{{ route('instructor.dashboard') }}">Dashboard</a>
                                </li>
                            @endrole

                            @role('student')
                                <li class="nav-item">
                                    <a class="nav-link fs-5 px-3" href="{{ route('student.dashboard') }}">Dashboard</a>
                                </li>
                            @endrole

                        @endauth

                    </ul>

                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                        @auth

                            <li class="nav-item dropdown">
                                <a
                                    href="#"
                                    id="userMenu"
                                    role="button"
                                    class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    @include('partials.user-avatar', ['size' => 44])
                                    <span class="text-light fs-5">
                                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                    </span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end fs-5" aria-labelledby="userMenu">

                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            Mon profil
                                        </a>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                Déconnexion
                                            </button>
                                        </form>
                                    </li>

                                </ul>
                            </li>

                        @else

                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('login') }}">Connexion</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('register') }}">Inscription</a>
                            </li>

                        @endauth

                    </ul>

                </div>

            </div>
        </header>

        <!-- Page heading (slot) -->
        @isset($header)
            <div class="bg-white border-bottom py-3">
                <div class="container-fluid">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Content -->
        <main class="flex-grow-1">
            <div class="container-fluid p-4">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-light text-center py-3 mt-auto">
            <small>
                &copy; {{ date('Y') }} {{ config('app.name', 'Quizz Militaire') }} — Plateforme Militaire d'Évaluation.
            </small>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
