<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //...
    public function index()
    {
        $users = User::with([
            'promotion'
        ])

        ->latest()

        ->paginate(20);

        return UserResource::collection(
            $users
        );
    }

    //Method to show
    public function show(User $user)
    {
        return new UserResource(
            $user->load('promotion')
        );
    }

    //Method to update
    public function update(Request $request, User $user)
    {
        $validated =
            $request->validate([

                'first_name' =>
                    'required',

                'last_name' =>
                    'required',

                'phone' =>
                    'nullable',

                'rank' =>
                    'nullable',

                'promotion_id' =>
                    'nullable|exists:promotions,id'
            ]);

        $user->update(
            $validated
        );

        return response()->json([

            'message' =>
                'Utilisateur modifié'
        ]);
    }

    //Method to activate user
    public function activate(User $user)
    {
        $user->update([

            'is_active' => true
        ]);

        return back();
    }

    //Method to deactivate user
    public function deactivate(User $user)
    {
        $user->update([

            'is_active' => false
        ]);

        return back();
    }

    public function resetPassword(User $user)
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

}
