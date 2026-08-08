<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $subjects = Subject::all();

        $promotions = Promotion::all();

        return view('auth.register', compact('subjects', 'promotions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
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
            'access_code' => ['required', 'string', Rule::in(array_keys($accessCodes))],
            'matricule' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'subject_ids' => [$isInstructor ? 'required' : 'nullable', 'array', 'min:1'],
            'subject_ids.*' => ['exists:subjects,id'],
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

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
