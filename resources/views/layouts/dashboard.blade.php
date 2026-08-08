<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
            @yield('title', 'Dashboard') — {{ config('app.name', 'Quizz Militaire') }}
        </title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Appliquer le thème AVANT le rendu pour éviter le flash -->
        <script>
            (function () {
                const t = localStorage.getItem('qm-theme') || 'dark';
                if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
            })();
        </script>

    </head>

    <style>
        /* ═══════════════════════════════════════
           THÈME CLAIR (défaut Bootstrap)
        ═══════════════════════════════════════ */
        body {
            background: #f8f9fa;
            color: #212529;
            transition: background 0.3s, color 0.3s;
        }
        .dash-navbar-light {
            background: #212529 !important;
        }
        .dash-footer-light {
            background: #212529;
            color: #adb5bd;
        }

        /* ═══════════════════════════════════════
           THÈME SOMBRE — activé par [data-theme="dark"] sur <html>
        ═══════════════════════════════════════ */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 40%, #16213e 70%, #0f3460 100%) fixed;
            color: #f1f5f9;
        }

        /* Navbar */
        [data-theme="dark"] .dash-navbar {
            background: rgba(0,0,0,0.75) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        [data-theme="dark"] .dash-navbar .nav-link  { color: #cbd5e1 !important; transition: color 0.2s; }
        [data-theme="dark"] .dash-navbar .nav-link:hover,
        [data-theme="dark"] .dash-navbar .nav-link.active { color: #f59e0b !important; }
        [data-theme="dark"] .dash-navbar .navbar-brand { color: #fff !important; }

        /* Dropdown */
        [data-theme="dark"] .dash-navbar .dropdown-menu {
            background: #1a1a2e;
            border: 1px solid rgba(255,255,255,0.12);
        }
        [data-theme="dark"] .dash-navbar .dropdown-item { color: #cbd5e1; }
        [data-theme="dark"] .dash-navbar .dropdown-item:hover { background: rgba(245,158,11,0.15); color: #f59e0b; }
        [data-theme="dark"] .dash-navbar .dropdown-divider { border-color: rgba(255,255,255,0.1); }

        /* Cards */
        [data-theme="dark"] .card {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #f1f5f9 !important;
            backdrop-filter: blur(6px);
        }
        [data-theme="dark"] .card-header {
            background: rgba(255,255,255,0.06) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
            color: #f1f5f9 !important;
            font-weight: 600;
        }

        /* Tables */
        [data-theme="dark"] .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(255,255,255,0.04);
            --bs-table-hover-bg: rgba(245,158,11,0.08);
            --bs-table-border-color: rgba(255,255,255,0.08);
            --bs-table-color: #e2e8f0;
            --bs-table-striped-color: #e2e8f0;
            --bs-table-hover-color: #f1f5f9;
            color: #e2e8f0 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        [data-theme="dark"] .table > :not(caption) > * > * {
            background-color: transparent !important;
            color: #e2e8f0 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        [data-theme="dark"] .table > thead > tr > * {
            background: rgba(245,158,11,0.12) !important;
            color: #f59e0b !important;
            font-weight: 600;
            border-color: rgba(255,255,255,0.12) !important;
        }
        [data-theme="dark"] .table > tbody > tr:hover > * {
            background: rgba(245,158,11,0.07) !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .table-bordered,
        [data-theme="dark"] .table-bordered > :not(caption) > * {
            border-color: rgba(255,255,255,0.08) !important;
        }

        /* Formulaires */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: rgba(255,255,255,0.08) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background: rgba(255,255,255,0.12) !important;
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 0.2rem rgba(245,158,11,0.25) !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .form-control::placeholder { color: #64748b !important; }
        [data-theme="dark"] .form-label  { color: #cbd5e1 !important; }
        [data-theme="dark"] .form-text   { color: #94a3b8 !important; }
        [data-theme="dark"] option { background: #1a1a2e; color: #f1f5f9; }

        /* Boutons */
        [data-theme="dark"] .btn-primary   { background: #f59e0b !important; border-color: #f59e0b !important; color: #000 !important; font-weight: 600; }
        [data-theme="dark"] .btn-primary:hover { background: #d97706 !important; border-color: #d97706 !important; }
        [data-theme="dark"] .btn-secondary { background: rgba(255,255,255,0.1) !important; border-color: rgba(255,255,255,0.2) !important; color: #f1f5f9 !important; }
        [data-theme="dark"] .btn-secondary:hover { background: rgba(255,255,255,0.18) !important; }
        [data-theme="dark"] .btn-link  { color: #f59e0b !important; }
        [data-theme="dark"] .btn-link:hover { color: #d97706 !important; }

        /* Alertes */
        [data-theme="dark"] .alert-success { background: rgba(16,185,129,0.15) !important; border-color: rgba(16,185,129,0.3) !important; color: #6ee7b7 !important; }
        [data-theme="dark"] .alert-danger  { background: rgba(239,68,68,0.15)  !important; border-color: rgba(239,68,68,0.3)  !important; color: #fca5a5 !important; }
        [data-theme="dark"] .alert-info    { background: rgba(59,130,246,0.15) !important; border-color: rgba(59,130,246,0.3) !important; color: #93c5fd !important; }
        [data-theme="dark"] .alert-warning { background: rgba(245,158,11,0.15) !important; border-color: rgba(245,158,11,0.3) !important; color: #fde68a !important; }

        /* Badges */
        [data-theme="dark"] .badge.bg-success { background: #059669 !important; }
        [data-theme="dark"] .badge.bg-danger  { background: #dc2626 !important; }
        [data-theme="dark"] .badge.bg-warning { background: #d97706 !important; color: #000 !important; }
        [data-theme="dark"] .badge.bg-info    { background: #0284c7 !important; }

        /* Pagination */
        [data-theme="dark"] .pagination .page-link { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); color: #cbd5e1; }
        [data-theme="dark"] .pagination .page-link:hover { background: rgba(245,158,11,0.2); color: #f59e0b; border-color: #f59e0b; }
        [data-theme="dark"] .pagination .page-item.active .page-link { background: #f59e0b; border-color: #f59e0b; color: #000; }
        [data-theme="dark"] .pagination .page-item.disabled .page-link { background: rgba(255,255,255,0.03); color: #475569; }

        /* Titres & textes */
        [data-theme="dark"] h1, [data-theme="dark"] h2,
        [data-theme="dark"] h3, [data-theme="dark"] h4,
        [data-theme="dark"] h5, [data-theme="dark"] h6 { color: #f1f5f9 !important; }
        [data-theme="dark"] .text-muted { color: #64748b !important; }

        /* Footer */
        [data-theme="dark"] .dash-footer { background: rgba(0,0,0,0.5); border-top: 1px solid rgba(255,255,255,0.07); color: #64748b; }

        /* list-group */
        [data-theme="dark"] .list-group-item { background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.08) !important; color: #e2e8f0 !important; }

        /* ── Bouton de bascule de thème ── */
        #theme-toggle {
            width: 38px; height: 22px;
            background: #495057;
            border: none; border-radius: 999px;
            position: relative; cursor: pointer;
            transition: background 0.3s;
            flex-shrink: 0;
        }
        #theme-toggle::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 16px; height: 16px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.3s;
        }
        [data-theme="dark"] #theme-toggle { background: #f59e0b; }
        [data-theme="dark"] #theme-toggle::after { transform: translateX(16px); }

        #theme-label { font-size: 0.8rem; color: #adb5bd; white-space: nowrap; }
        [data-theme="dark"] #theme-label { color: #94a3b8; }
    </style>

    <body class="d-flex flex-column min-vh-100">

        <!-- Header -->
        <header class="navbar navbar-expand-lg navbar-dark dash-navbar dash-navbar-light py-3">
            <div class="container-fluid">

                <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" style="font-size: clamp(1rem, 3vw, 1.4rem);" href="{{ route('dashboard') }}">
                    <span style="font-size: clamp(1.2rem, 4vw, 1.6rem);">🎖️</span>
                    <span class="d-none d-sm-inline">{{ config('app.name', 'Quizz Militaire') }}</span>
                    <span class="d-inline d-sm-none">QM</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">

                    <ul class="navbar-nav me-auto align-items-lg-center gap-lg-2">

                        @role('admin')
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('admin.promotions.index') }}">Promotions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('admin.subjects.index') }}">Matières</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('admin.instructors.index') }}">Matières des instructeurs</a>
                            </li>
                        @endrole

                        @role('instructor_l1|instructor_l2')
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('instructor.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('instructor.quizzes.index') }}">Mes quiz</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('instructor.students.index') }}">Mes stagiaires</a>
                            </li>
                        @endrole

                        @role('instructor_l2')
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('instructor.l1-management.index') }}">Gérer les matières L1</a>
                            </li>
                        @endrole

                        @role('student')
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('student.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('student.quiz.configuration') }}">Passer un quiz</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('student.history.index') }}">Historique</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-5 px-3" href="{{ route('student.statistics.index') }}">Statistiques</a>
                            </li>
                        @endrole

                    </ul>

                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                        <!-- Bascule de thème -->
                        <li class="nav-item d-flex align-items-center gap-2 px-2">
                            <span id="theme-label">☀️ Clair</span>
                            <button id="theme-toggle" aria-label="Basculer le thème"></button>
                        </li>

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
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Mon profil</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>

                </div>

            </div>
        </header>

        <!-- Content -->
        <main class="flex-grow-1">
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="dash-footer dash-footer-light text-center py-3 mt-auto">
            <small>
                &copy; {{ date('Y') }} {{ config('app.name', 'Quizz Militaire') }} — Plateforme Militaire d'Évaluation.
            </small>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            (function () {
                const html   = document.documentElement;
                const btn    = document.getElementById('theme-toggle');
                const label  = document.getElementById('theme-label');

                function applyTheme(theme) {
                    if (theme === 'dark') {
                        html.setAttribute('data-theme', 'dark');
                        label.textContent = '☀️ Clair';
                    } else {
                        html.removeAttribute('data-theme');
                        label.textContent = '🌙 Sombre';
                    }
                    localStorage.setItem('qm-theme', theme);
                }

                // État initial (déjà appliqué en <head>, on met juste le label à jour)
                const current = localStorage.getItem('qm-theme') || 'dark';
                applyTheme(current);

                btn.addEventListener('click', () => {
                    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    applyTheme(next);
                });
            })();
        </script>

    </body>

</html>
