<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with([
            'promotion'
        ])

        ->latest()

        ->paginate(15);

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function show(
        User $user
    )
    {
        return view(
            'admin.users.show',
            compact('user')
        );
    }

    public function edit(
        User $user
    )
    {
        $promotions =
            Promotion::all();

        $user->loadMissing('profile');

        return view(
            'admin.users.edit',
            compact(
                'user',
                'promotions'
            )
        );
    }

    public function update(
        Request $request,
        User $user
    )
    {
        $validated =
            $request->validate([

                'first_name' =>
                    'required',

                'last_name' =>
                    'required',

                'email' =>
                    'required|email',

                'promotion_id' =>
                    'nullable|exists:promotions,id',

                'avatar' =>
                    'nullable|image|max:2048'
            ]);

        $user->update(
            collect($validated)->except('avatar')->all()
        );

        if ($request->hasFile('avatar')) {

            if ($user->profile?->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['avatar' => $request->file('avatar')->store('avatars', 'public')]
            );
        }

        return redirect()

            ->route(
                'admin.users.index'
            )

            ->with(
                'success',
                'Utilisateur modifié'
            );
    }

    public function activate(
        User $user
    )
    {
        $user->update([
            'is_active' => true
        ]);

        return back()
            ->with(
                'success',
                'Utilisateur activé'
            );
    }

    public function deactivate(
        User $user
    )
    {
        $user->update([
            'is_active' => false
        ]);

        return back()
            ->with(
                'success',
                'Utilisateur désactivé'
            );
    }

    public function resetPassword(
        User $user
    )
    {
        $user->update([
            'password' =>
                Hash::make(
                    'Password123'
                )
        ]);

        return back()
            ->with(
                'success',
                'Mot de passe réinitialisé'
            );
    }

    public function destroy(
        User $user
    )
    {
        abort_if(
            $user->id === auth()->id(),
            403,
            'Vous ne pouvez pas supprimer votre propre compte.'
        );

        if ($user->profile?->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }

        $user->delete();

        return redirect()

            ->route(
                'admin.users.index'
            )

            ->with(
                'success',
                'Utilisateur supprimé'
            );
    }
}