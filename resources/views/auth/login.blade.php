<x-guest-layout>

    <style>
        /* Champs de saisie sur fond sombre */
        .guest-input {
            width: 100%;
            margin-top: 0.25rem;
            padding: 0.55rem 0.75rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 0.5rem;
            color: #f9fafb;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .guest-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,0.25); }
        .guest-input::placeholder { color: #9ca3af; }
        .guest-label { display: block; font-size: 0.875rem; font-weight: 500; color: #d1d5db; margin-bottom: 0.25rem; }
        .guest-error { color: #f87171; font-size: 0.8rem; margin-top: 0.25rem; }
        .guest-btn {
            padding: 0.55rem 1.5rem;
            background: #f59e0b;
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .guest-btn:hover { background: #d97706; }
    </style>

    <!-- Retour -->
    <a href="{{ url('/') }}" style="display:inline-flex; align-items:center; font-size:0.875rem; color:#9ca3af; text-decoration:none; margin-bottom:1.25rem;">
        &larr; Retour à l'accueil
    </a>

    <h2 style="font-size:1.4rem; font-weight:700; color:#fff; margin-bottom:1.5rem; text-align:center;">Connexion</h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="guest-label">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="guest-input" placeholder="votre@email.com">
            @error('email') <p class="guest-error">{{ $message }}</p> @enderror
        </div>

        <!-- Mot de passe -->
        <div style="margin-top:1rem;">
            <label for="password" class="guest-label">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="guest-input" placeholder="••••••••">
            @error('password') <p class="guest-error">{{ $message }}</p> @enderror
        </div>

        <!-- Se souvenir de moi -->
        <div style="margin-top:1rem;">
            <label style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.875rem; color:#d1d5db; cursor:pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="accent-color:#f59e0b;">
                Se souvenir de moi
            </label>
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between; margin-top:1.5rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:0.85rem; color:#9ca3af; text-decoration:underline;">
                    Mot de passe oublié ?
                </a>
            @endif
            <button type="submit" class="guest-btn">Se connecter</button>
        </div>
    </form>

</x-guest-layout>
