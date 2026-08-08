<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RedirectsToDashboard;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    use RedirectsToDashboard;
    /**
     * Met à jour (ou crée) les informations complémentaires de l'utilisateur connecté.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'country' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'education_level' => ['nullable', 'in:' . implode(',', UserProfile::EDUCATION_LEVELS)],
            'previous_profession' => ['nullable', 'in:' . implode(',', UserProfile::PREVIOUS_PROFESSIONS)],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {

            if ($user->profile?->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            $validated['avatar'] = $request->file('avatar')
                ->store('avatars', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return $this->redirectToDashboard('success', 'Informations complémentaires enregistrées.');
    }
}
