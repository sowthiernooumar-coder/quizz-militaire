<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\LoginLog;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $accessCodes = config('access_codes');

        $role = $accessCodes[$request->access_code]
            ?? null;

        $isInstructor = in_array(
            $role,
            ['instructor_l1', 'instructor_l2']
        );

        $isStudent = $role === 'student';

        $validated = $request->validate([
            'access_code' => ['required', 'string', 'in:'.implode(',', array_keys($accessCodes))],
            'matricule' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subject_ids' => [$isInstructor ? 'required' : 'nullable', 'array', 'min:1'],
            'subject_ids.*' => 'exists:subjects,id',
            'promotion_id' => [$isStudent ? 'required' : 'nullable', 'exists:promotions,id'],
        ], [
            'access_code.in' => 'Code d\'accès invalide.',
            'subject_ids.required' => 'Veuillez choisir au moins une matière.',
            'promotion_id.required' => 'Veuillez choisir votre promotion.',
        ]);

        $role = $accessCodes[$validated['access_code']];

        $user = User::create([
            'matricule' => $validated['matricule'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'promotion_id' => $role === 'student'
                ? ($validated['promotion_id'] ?? null)
                : null,
        ]);

        $user->assignRole($role);

        if (in_array($role, ['instructor_l1', 'instructor_l2'])) {
            $user->subjects()->sync($validated['subject_ids'] ?? []);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        LoginLog::create([

            'user_id' =>
                Auth::id(),

            'ip_address' =>
                $request->ip(),

            'login_at' =>
                now()
        ]);

        $token = Auth::user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    

    public function logout(Request $request)
    {
        $user = Auth::user();

        $user->tokens()->delete();

        LoginLog::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->update(['logout_at' => now()]);

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

}
