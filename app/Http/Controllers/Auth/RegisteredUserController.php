<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentAuth;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:student_auths'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if email exists in students table and is enrolled
        $student = Student::where('email', $request->email)
            ->where('status', 'Enrolled')
            ->first();

        if ($student) {
            // Create student auth account
            $studentAuth = StudentAuth::create([
                'name' => $student->name,
                'email' => $student->email,
                'password' => Hash::make($request->password),
                'student_id' => $student->id
            ]);

            event(new Registered($studentAuth));
            Auth::guard('student')->login($studentAuth);
            return redirect()->route('student.grades');
        }

        // If not a student, create admin user
        $user = User::create([
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
