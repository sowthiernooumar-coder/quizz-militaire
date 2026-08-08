<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations complémentaires') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ces informations sont facultatives et peuvent être renseignées ou modifiées à tout moment.') }}
        </p>
    </header>

    @php
        $profile = $user->profile;
    @endphp

    <form method="post" action="{{ route('profile.details.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div>
            <x-input-label for="avatar" :value="__('Photo de profil')" />

            @if($profile?->avatar)
                <img src="{{ asset('storage/' . $profile->avatar) }}" alt="Photo de profil" class="w-20 h-20 rounded-full object-cover mt-2 mb-2">
            @endif

            <input type="file" id="avatar" name="avatar" accept="image/*" class="block w-full text-sm text-gray-700 mt-1">
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="birth_date" :value="__('Date de naissance')" />
            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $profile?->birth_date?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <div>
            <x-input-label for="birth_place" :value="__('Lieu de naissance')" />
            <x-text-input id="birth_place" name="birth_place" type="text" class="mt-1 block w-full" :value="old('birth_place', $profile?->birth_place)" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_place')" />
        </div>

        <div>
            <x-input-label for="country" :value="__('Pays')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $profile?->country)" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Sexe')" />
            <select id="gender" name="gender" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                <option value="">{{ __('— Non précisé —') }}</option>
                <option value="Homme" @selected(old('gender', $profile?->gender) === 'Homme')>{{ __('Homme') }}</option>
                <option value="Femme" @selected(old('gender', $profile?->gender) === 'Femme')>{{ __('Femme') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <div>
            <x-input-label for="marital_status" :value="__('Situation matrimoniale')" />
            <select id="marital_status" name="marital_status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                <option value="">{{ __('— Non précisé —') }}</option>
                @foreach(['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf/Veuve'] as $status)
                    <option value="{{ $status }}" @selected(old('marital_status', $profile?->marital_status) === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('marital_status')" />
        </div>

        <div>
            <x-input-label for="education_level" :value="__('Niveau d\'étude')" />
            <select id="education_level" name="education_level" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                <option value="">{{ __('— Non précisé —') }}</option>
                @foreach(\App\Models\UserProfile::EDUCATION_LEVELS as $level)
                    <option value="{{ $level }}" @selected(old('education_level', $profile?->education_level) === $level)>{{ $level }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('education_level')" />
        </div>

        <div>
            <x-input-label for="previous_profession" :value="__('Ancienne profession')" />
            <select id="previous_profession" name="previous_profession" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                <option value="">{{ __('— Non précisé —') }}</option>
                @foreach(\App\Models\UserProfile::PREVIOUS_PROFESSIONS as $profession)
                    <option value="{{ $profession }}" @selected(old('previous_profession', $profile?->previous_profession) === $profession)>{{ $profession }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('previous_profession')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
        </div>
    </form>
</section>
