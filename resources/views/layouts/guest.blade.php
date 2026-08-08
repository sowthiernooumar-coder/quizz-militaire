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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0" style="background: linear-gradient(135deg, #000000 0%, #1a1a2e 40%, #16213e 70%, #0f3460 100%);">

            <!-- Header -->
            <header class="mt-8 mb-2 text-center">
                <a href="/" class="text-2xl font-bold text-white tracking-wide flex items-center justify-center gap-2">
                    <span style="font-size:2rem;">🎖️</span> {{ config('app.name', 'Quizz Militaire') }}
                </a>
            </header>

            <!-- Content -->
            <main class="w-full sm:max-w-md mt-6 px-6 py-6 overflow-hidden sm:rounded-xl"
                  style="background: rgba(255,255,255,0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.12); box-shadow: 0 8px 32px rgba(0,0,0,0.5);">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="mt-6 mb-6 text-sm text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'Quizz Militaire') }}
            </footer>

        </div>
    </body>
</html>
