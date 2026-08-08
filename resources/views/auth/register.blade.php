<x-guest-layout>

    <!-- Retour -->
    <a href="{{ url('/') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
        &larr; {{ __('Retour à l\'accueil') }}
    </a>

    @php
        $resolvedRole = old('access_code')
            ? (config('access_codes')[old('access_code')] ?? null)
            : null;
    @endphp

    <div
        x-data="{
            modalOpen: {{ old('access_code') ? 'false' : 'true' }},
            code: '',
            error: {{ $errors->get('access_code') ? json_encode($errors->first('access_code')) : 'null' }},
            loading: false,
            accessCode: {{ json_encode(old('access_code', '')) }},
            role: {{ json_encode($resolvedRole) }},

            get isInstructor() {
                return this.role === 'instructor_l1' || this.role === 'instructor_l2';
            },

            get isStudent() {
                return this.role === 'student';
            },

            verifyCode() {
                this.error = null;
                this.loading = true;

                fetch('{{ route('access-code.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ code: this.code }),
                })
                    .then(async (response) => {
                        const data = await response.json();

                        if (response.ok && data.valid) {
                            this.accessCode = this.code;
                            this.role = data.role;
                            this.modalOpen = false;
                        } else {
                            this.error = data.message ?? 'Code d\'accès invalide.';
                        }
                    })
                    .catch(() => {
                        this.error = 'Une erreur est survenue, veuillez réessayer.';
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
        }"
    >

        <!-- Modal: saisie obligatoire du code d'accès -->
        <div
            x-show="modalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4"
        >
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6">

                <h2 class="text-lg font-semibold text-gray-900 mb-1">
                    {{ __('Code d\'accès requis') }}
                </h2>

                <p class="text-sm text-gray-500 mb-4">
                    {{ __('Veuillez saisir votre code d\'accès pour continuer votre inscription. Ce code déterminera votre catégorie : Stagiaire militaire, Instructeur L1, Instructeur L2 ou Administrateur.') }}
                </p>

                <form @submit.prevent="verifyCode">

                    <x-input-label for="modal_access_code" :value="__('Code d\'accès')" />

                    <input
                        id="modal_access_code"
                        type="text"
                        x-model="code"
                        autofocus
                        autocomplete="off"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                    >

                    <p x-show="error" x-text="error" class="text-sm text-red-600 mt-2"></p>

                    <button
                        type="submit"
                        :disabled="loading || code.length === 0"
                        class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    >
                        <span x-show="!loading">{{ __('Valider') }}</span>
                        <span x-show="loading">{{ __('Vérification...') }}</span>
                    </button>

                </form>

            </div>
        </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Access code (saisi via la modale) -->
        <input type="hidden" name="access_code" :value="accessCode">

        <!-- Matricule -->
        <div class="mt-4">
            <x-input-label for="matricule" :value="__('Matricule')" />
            <x-text-input id="matricule" class="block mt-1 w-full" type="text" name="matricule" :value="old('matricule')" required autocomplete="off" />
            <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
        </div>

        <!-- First name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Matières (instructeurs L1 / L2 uniquement) -->
        <div class="mt-4" x-show="isInstructor" x-cloak>
            <x-input-label :value="__('Matière(s)')" />

            <p class="text-sm text-gray-500 mt-1">
                {{ __('Sélectionnez une ou plusieurs matières que vous enseignerez.') }}
            </p>

            <div class="mt-2 space-y-1">
                @foreach($subjects as $subject)
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="subject_ids[]"
                            value="{{ $subject->id }}"
                            @checked(in_array($subject->id, old('subject_ids', [])))
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        >
                        <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                    </label>
                @endforeach
            </div>

            <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
        </div>

        <!-- Promotion (stagiaires militaires uniquement) -->
        <div class="mt-4" x-show="isStudent" x-cloak>
            <x-input-label for="promotion_id" :value="__('Promotion')" />

            <select
                id="promotion_id"
                name="promotion_id"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
            >
                <option value="">{{ __('— Sélectionnez votre promotion —') }}</option>

                @foreach($promotions as $promotion)
                    <option value="{{ $promotion->id }}" @selected(old('promotion_id') == $promotion->id)>
                        {{ $promotion->name }}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('promotion_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    </div>

</x-guest-layout>
