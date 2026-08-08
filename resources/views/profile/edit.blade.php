@extends('layouts.dashboard')

@section('title', 'Mon profil')

@section('content')

{{-- Bouton retour --}}
@php
    $backRoute = match(true) {
        auth()->user()->hasRole('admin')         => route('admin.dashboard'),
        auth()->user()->hasRole('instructor_l2') => route('instructor.dashboard'),
        auth()->user()->hasRole('instructor_l1') => route('instructor.dashboard'),
        default                                  => route('student.dashboard'),
    };
@endphp

<a href="{{ $backRoute }}" class="btn btn-secondary btn-sm mb-4">
    &larr; Retour au dashboard
</a>

<h2 class="mb-4">Mon profil</h2>

@if(session('status') === 'profile-updated')
    <div class="alert alert-success">Informations mises à jour.</div>
@endif
@if(session('status') === 'password-updated')
    <div class="alert alert-success">Mot de passe mis à jour.</div>
@endif

<div class="row g-4">

    {{-- ── Informations principales ── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>📋 Informations du compte</strong>
                <p class="mb-0 mt-1 small text-muted">Nom, prénom et adresse email.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                   value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nom</label>
                            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                   value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Informations complémentaires ── --}}
    <div class="col-12">
        @php $profile = $user->profile; @endphp
        <div class="card">
            <div class="card-header">
                <strong>🪪 Informations complémentaires</strong>
                <p class="mb-0 mt-1 small text-muted">Ces informations sont facultatives.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.details.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Avatar --}}
                        <div class="col-12">
                            <label class="form-label">Photo de profil</label>
                            @if($profile?->avatar)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $profile->avatar) }}"
                                         alt="Photo de profil"
                                         class="rounded-circle object-fit-cover"
                                         style="width:300px; height:300px; border:4px solid rgba(255,255,255,0.2); box-shadow:0 0 20px rgba(0,0,0,0.5);">
                                </div>
                            @endif
                            <input type="file" name="avatar" accept="image/*" class="form-control @error('avatar') is-invalid @enderror">
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Date de naissance</label>
                            <input type="date" id="birth_date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                                   value="{{ old('birth_date', $profile?->birth_date?->format('Y-m-d')) }}">
                            @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="birth_place" class="form-label">Lieu de naissance</label>
                            <input type="text" id="birth_place" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror"
                                   value="{{ old('birth_place', $profile?->birth_place) }}">
                            @error('birth_place') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="country" class="form-label">Pays</label>
                            <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror"
                                   value="{{ old('country', $profile?->country) }}">
                            @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Sexe</label>
                            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">— Non précisé —</option>
                                @foreach(['Homme','Femme'] as $g)
                                    <option value="{{ $g }}" @selected(old('gender', $profile?->gender) === $g)>{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="marital_status" class="form-label">Situation matrimoniale</label>
                            <select id="marital_status" name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                <option value="">— Non précisé —</option>
                                @foreach(['Célibataire','Marié(e)','Divorcé(e)','Veuf/Veuve'] as $s)
                                    <option value="{{ $s }}" @selected(old('marital_status', $profile?->marital_status) === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="education_level" class="form-label">Niveau d'étude</label>
                            <select id="education_level" name="education_level" class="form-select @error('education_level') is-invalid @enderror">
                                <option value="">— Non précisé —</option>
                                @foreach(\App\Models\UserProfile::EDUCATION_LEVELS as $level)
                                    <option value="{{ $level }}" @selected(old('education_level', $profile?->education_level) === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                            @error('education_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="previous_profession" class="form-label">Ancienne profession</label>
                            <select id="previous_profession" name="previous_profession" class="form-select @error('previous_profession') is-invalid @enderror">
                                <option value="">— Non précisé —</option>
                                @foreach(\App\Models\UserProfile::PREVIOUS_PROFESSIONS as $p)
                                    <option value="{{ $p }}" @selected(old('previous_profession', $profile?->previous_profession) === $p)>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('previous_profession') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Mot de passe ── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>🔒 Changer le mot de passe</strong>
                <p class="mb-0 mt-1 small text-muted">Utilisez un mot de passe long et aléatoire.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" id="new_password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Confirmer</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Supprimer le compte ── --}}
    <div class="col-12">
        <div class="card" style="border-color: rgba(220,53,69,0.4) !important;">
            <div class="card-header" style="border-color: rgba(220,53,69,0.4) !important;">
                <strong class="text-danger">⚠️ Supprimer le compte</strong>
                <p class="mb-0 mt-1 small text-muted">Cette action est irréversible. Toutes les données seront supprimées.</p>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    Supprimer mon compte
                </button>
            </div>
        </div>
    </div>

</div>

{{-- Modal confirmation suppression --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#1a1a2e; border:1px solid rgba(255,255,255,0.12); color:#f1f5f9;">
            <div class="modal-header" style="border-color:rgba(255,255,255,0.1);">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Êtes-vous sûr(e) de vouloir supprimer votre compte ? Cette action est <strong>irréversible</strong>.</p>
                    <label for="delete_password" class="form-label">Confirmez avec votre mot de passe</label>
                    <input type="password" id="delete_password" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Mot de passe">
                    @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="modal-footer" style="border-color:rgba(255,255,255,0.1);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
