<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.student-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:student_auths',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if student exists and is enrolled
        $student = Student::where('email', $request->email)
            ->where('status', 'Enrolled')
            ->first();

        if (!$student) {
            // If not a student email, create admin user instead
            $user = User::create([
                'name' => explode('@', $request->email)[0],
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin'
            ]);

            Auth::login($user);
            return redirect()->route('dashboard');
        }

        // Create student auth account
        $studentAuth = StudentAuth::create([
            'name' => $student->name,
            'email' => $student->email,
            'password' => Hash::make($request->password),
            'student_id' => $student->id
        ]);

        Auth::guard('student')->login($studentAuth);
        return redirect()->route('student.grades');
    }
} 