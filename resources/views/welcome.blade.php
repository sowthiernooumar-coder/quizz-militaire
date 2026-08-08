<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Quizz Militaire') }} — Plateforme Militaire d'Évaluation</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans antialiased text-gray-900 bg-gray-50 bg-fixed bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('images/10.jpeg') }}');"
    >

        <!-- Header -->
        <header class="bg-gray-900/90 text-white sticky top-0 z-40 shadow-lg">
            <div class="max-w-7xl mx-auto px-6 py-4" style="display:flex; align-items:center; justify-content:space-between; gap:1.5rem;">

                <!-- Logo -->
                <a href="{{ url('/') }}" class="text-2xl font-semibold tracking-wide" style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
                    <span style="font-size:1.75rem;">🎖️</span> {{ config('app.name', 'Quizz Militaire') }}
                </a>

                <!-- Nav + Auth desktop -->
                <div id="desktop-nav" style="display:flex; align-items:center; gap:0.5rem; flex:1; justify-content:flex-end;">

                    <nav id="section-nav" style="display:flex; align-items:center;">
                        <a href="#armee"          data-section="armee"          class="section-card" style="padding:0.5rem 1rem; font-size:0.95rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:2px solid transparent; transition:all 0.2s;">Armée</a>
                        <a href="#formation"      data-section="formation"      class="section-card" style="padding:0.5rem 1rem; font-size:0.95rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:2px solid transparent; transition:all 0.2s;">Formation militaire</a>
                        <a href="#collaborateurs" data-section="collaborateurs" class="section-card" style="padding:0.5rem 1rem; font-size:0.95rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:2px solid transparent; transition:all 0.2s;">Collaborateurs</a>
                        <a href="#apropos"        data-section="apropos"        class="section-card" style="padding:0.5rem 1rem; font-size:0.95rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:2px solid transparent; transition:all 0.2s;">A propos de nous</a>
                    </nav>

                    <nav style="display:flex; align-items:center; gap:1rem; flex-shrink:0; margin-left:1rem;">
                        <a href="{{ route('login') }}" style="font-size:0.9rem; font-weight:500; color:#d1d5db; text-decoration:none;">Connexion</a>
                        <a href="{{ route('register') }}" style="padding:0.4rem 1rem; background:#f59e0b; border-radius:0.375rem; font-size:0.9rem; font-weight:600; color:#fff; text-decoration:none;">Inscription</a>
                    </nav>

                </div>

                <!-- Bouton hamburger (mobile uniquement) -->
                <button id="menu-toggle" aria-label="Menu" style="display:none; flex-direction:column; justify-content:center; align-items:center; gap:5px; width:36px; height:36px; background:none; border:none; cursor:pointer; flex-shrink:0;">
                    <span class="bar" style="display:block; width:24px; height:2px; background:#fff; transition:all 0.3s;"></span>
                    <span class="bar" style="display:block; width:24px; height:2px; background:#fff; transition:all 0.3s;"></span>
                    <span class="bar" style="display:block; width:24px; height:2px; background:#fff; transition:all 0.3s;"></span>
                </button>

            </div>

            <!-- Menu mobile déroulant -->
            <div id="mobile-menu" style="display:none; flex-direction:column; gap:0; background:rgba(17,24,39,0.97); border-top:1px solid #374151; padding:0.5rem 1.5rem 1rem;">
                <a href="#armee"          data-section="armee"          class="section-card mobile-link" style="padding:0.85rem 0.5rem; font-size:1rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:1px solid #374151;">Armée</a>
                <a href="#formation"      data-section="formation"      class="section-card mobile-link" style="padding:0.85rem 0.5rem; font-size:1rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:1px solid #374151;">Formation militaire</a>
                <a href="#collaborateurs" data-section="collaborateurs" class="section-card mobile-link" style="padding:0.85rem 0.5rem; font-size:1rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:1px solid #374151;">Collaborateurs</a>
                <a href="#apropos"        data-section="apropos"        class="section-card mobile-link" style="padding:0.85rem 0.5rem; font-size:1rem; font-weight:500; color:#d1d5db; text-decoration:none; border-bottom:1px solid #374151;">A propos de nous</a>
                <div style="display:flex; gap:0.75rem; padding-top:0.75rem;">
                    <a href="{{ route('login') }}"    style="flex:1; text-align:center; padding:0.5rem; border:1px solid #6b7280; border-radius:0.375rem; font-size:0.9rem; color:#d1d5db; text-decoration:none;">Connexion</a>
                    <a href="{{ route('register') }}" style="flex:1; text-align:center; padding:0.5rem; background:#f59e0b; border-radius:0.375rem; font-size:0.9rem; font-weight:600; color:#fff; text-decoration:none;">Inscription</a>
                </div>
            </div>

        </header>

        <style>
            /* Hover sur les liens de section desktop */
            #section-nav .section-card:hover { color: #fbbf24; border-bottom-color: #fbbf24; }
            /* Hover sur les liens mobile */
            #mobile-menu .section-card:hover { color: #fbbf24; }
            /* Hover sur le bouton Connexion desktop */
            #desktop-nav a[href="{{ route('login') }}"]:hover { color: #fff; }
            /* Hover Inscription */
            #desktop-nav a[href="{{ route('register') }}"]:hover { background:#d97706; }

            @media (max-width: 767px) {
                #desktop-nav  { display: none !important; }
                #menu-toggle  { display: flex !important; }
            }
        </style>

        <!-- Hero -->
        <section class="bg-gray-900/80 text-white">
            <div class="max-w-6xl mx-auto px-6 py-16 text-center">

                <h1 class="text-3xl sm:text-4xl font-bold mb-4">
                    Plateforme Militaire d'Évaluation
                </h1>

                <p class="max-w-2xl mx-auto text-gray-300 text-lg">
                    Évaluez les connaissances des stagiaires militaires grâce à des quiz ciblés
                    par matière et par niveau, avec un suivi statistique complet des performances.
                </p>
                <!-- #Bouton register et login masquée
                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ route('register') }}"
                       class="px-6 py-3 rounded-lg bg-amber-500 hover:bg-amber-600 font-medium transition">
                        Créer un compte
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 rounded-lg border border-gray-500 hover:bg-gray-800 font-medium transition">
                        Se connecter
                    </a>
                </div> -->

            </div>
        </section>

        <!-- Section : Armée -->
        <section id="armee" class="scroll-mt-40 py-16 bg-emerald-950/60 text-white">
            <div class="max-w-4xl mx-auto px-6">

                <h2 class="text-2xl sm:text-3xl font-bold mb-4">
                    Armée
                </h2>

                <p class="text-gray-200 text-lg leading-relaxed">
                    Notre plateforme accompagne les forces armées dans le suivi rigoureux de la
                    progression de leurs stagiaires et de leurs cadres. Elle s'inscrit dans une
                    démarche d'excellence et de discipline, valeurs fondamentales de l'institution
                    militaire, en offrant un outil moderne d'évaluation des connaissances adapté
                    aux exigences du terrain.
                    Notre plateforme accompagne les forces armées dans le suivi rigoureux de la
                    progression de leurs stagiaires et de leurs cadres. Elle s'inscrit dans une
                    démarche d'excellence et de discipline, valeurs fondamentales de l'institution
                    militaire, en offrant un outil moderne d'évaluation des connaissances adapté
                    aux exigences du terrain.
                    Notre plateforme accompagne les forces armées dans le suivi rigoureux de la
                    progression de leurs stagiaires et de leurs cadres. Elle s'inscrit dans une
                    démarche d'excellence et de discipline, valeurs fondamentales de l'institution
                    militaire, en offrant un outil moderne d'évaluation des connaissances adapté
                    aux exigences du terrain.
                </p>

            </div>
        </section>

        <!-- Section : Formation militaire -->
        <section id="formation" class="scroll-mt-40 py-16 bg-slate-800/60 text-white">
            <div class="max-w-4xl mx-auto px-6">

                <h2 class="text-2xl sm:text-3xl font-bold mb-4">
                    Formation militaire
                </h2>

                <p class="text-gray-200 text-lg leading-relaxed">
                    Les quiz sont organisés par matière (topographie, armement, tactique...) et par
                    niveau de difficulté, permettant aux instructeurs de bâtir un parcours de
                    formation progressif et structuré. Chaque session d'évaluation contribue au
                    développement des compétences techniques et tactiques des stagiaires.
                    Les quiz sont organisés par matière (topographie, armement, tactique...) et par
                    niveau de difficulté, permettant aux instructeurs de bâtir un parcours de
                    formation progressif et structuré. Chaque session d'évaluation contribue au
                    développement des compétences techniques et tactiques des stagiaires.
                    Les quiz sont organisés par matière (topographie, armement, tactique...) et par
                    niveau de difficulté, permettant aux instructeurs de bâtir un parcours de
                    formation progressif et structuré. Chaque session d'évaluation contribue au
                    développement des compétences techniques et tactiques des stagiaires.
                </p>

            </div>
        </section>

        <!-- Section : Collaborateurs -->
        <section id="collaborateurs" class="scroll-mt-40 py-16 bg-amber-950/60 text-white">
            <div class="max-w-4xl mx-auto px-6">

                <h2 class="text-2xl sm:text-3xl font-bold mb-4">
                    Collaborateurs
                </h2>

                <p class="text-gray-200 text-lg leading-relaxed">
                    La plateforme repose sur une chaîne de responsabilités claire : les
                    administrateurs supervisent l'ensemble du dispositif, les instructeurs de
                    niveau 2 encadrent et répartissent les matières entre instructeurs de niveau 1,
                    qui conçoivent eux-mêmes les quiz destinés aux stagiaires. Chacun dispose d'un
                    espace de travail adapté à son rôle.
                    La plateforme repose sur une chaîne de responsabilités claire : les
                    administrateurs supervisent l'ensemble du dispositif, les instructeurs de
                    niveau 2 encadrent et répartissent les matières entre instructeurs de niveau 1,
                    qui conçoivent eux-mêmes les quiz destinés aux stagiaires. Chacun dispose d'un
                    espace de travail adapté à son rôle.
                    La plateforme repose sur une chaîne de responsabilités claire : les
                    administrateurs supervisent l'ensemble du dispositif, les instructeurs de
                    niveau 2 encadrent et répartissent les matières entre instructeurs de niveau 1,
                    qui conçoivent eux-mêmes les quiz destinés aux stagiaires. Chacun dispose d'un
                    espace de travail adapté à son rôle.
                </p>

            </div>
        </section>

        <!-- Section : A propos de nous -->
        <section id="apropos" class="scroll-mt-40 py-16 bg-zinc-900/60 text-white">
            <div class="max-w-4xl mx-auto px-6">

                <h2 class="text-2xl sm:text-3xl font-bold mb-4">
                    A propos de nous
                </h2>

                <p class="text-gray-200 text-lg leading-relaxed">
                    {{ config('app.name', 'Quizz Militaire') }} est une plateforme conçue pour
                    moderniser l'évaluation des connaissances au sein des structures de formation
                    militaire. Notre objectif est de fournir un outil simple, fiable et précis pour
                    mesurer la progression de chaque stagiaire et faciliter le travail des
                    instructeurs comme des administrateurs.
                    {{ config('app.name', 'Quizz Militaire') }} est une plateforme conçue pour
                    moderniser l'évaluation des connaissances au sein des structures de formation
                    militaire. Notre objectif est de fournir un outil simple, fiable et précis pour
                    mesurer la progression de chaque stagiaire et faciliter le travail des
                    instructeurs comme des administrateurs.
                    {{ config('app.name', 'Quizz Militaire') }} est une plateforme conçue pour
                    moderniser l'évaluation des connaissances au sein des structures de formation
                    militaire. Notre objectif est de fournir un outil simple, fiable et précis pour
                    mesurer la progression de chaque stagiaire et faciliter le travail des
                    instructeurs comme des administrateurs.
                </p>

            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900/90 text-gray-400 text-sm">
            <div class="max-w-6xl mx-auto px-6 py-6 text-center">
                &copy; {{ date('Y') }} {{ config('app.name', 'Quizz Militaire') }} — Plateforme Militaire d'Évaluation.
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                /* ── Hamburger ── */
                const toggle     = document.getElementById('menu-toggle');
                const mobileMenu = document.getElementById('mobile-menu');
                const bars       = toggle.querySelectorAll('.bar');
                let menuOpen     = false;

                toggle.addEventListener('click', () => {
                    menuOpen = !menuOpen;
                    mobileMenu.style.display = menuOpen ? 'flex' : 'none';

                    // Animation ☰ → ✕
                    bars[0].style.transform = menuOpen ? 'translateY(7px) rotate(45deg)'  : '';
                    bars[1].style.opacity   = menuOpen ? '0' : '';
                    bars[2].style.transform = menuOpen ? 'translateY(-7px) rotate(-45deg)' : '';
                });

                // Fermer le menu au clic sur un lien mobile
                document.querySelectorAll('.mobile-link').forEach(link => {
                    link.addEventListener('click', () => {
                        menuOpen = false;
                        mobileMenu.style.display = 'none';
                        bars[0].style.transform = '';
                        bars[1].style.opacity   = '';
                        bars[2].style.transform = '';
                    });
                });

                /* ── Scrollspy ── */
                const cards = document.querySelectorAll('.section-card');
                const sections = document.querySelectorAll('section[id]');

                const setActive = (id) => {
                    cards.forEach((card) => {
                        if (card.dataset.section === id) {
                            card.style.color             = '#fbbf24';
                            card.style.borderBottomColor = '#fbbf24';
                        } else {
                            card.style.color             = '#d1d5db';
                            card.style.borderBottomColor = 'transparent';
                        }
                    });
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            setActive(entry.target.id);
                        }
                    });
                }, {
                    rootMargin: '-45% 0px -45% 0px',
                    threshold: 0,
                });

                sections.forEach((section) => observer.observe(section));
            });
        </script>

    </body>
</html>
