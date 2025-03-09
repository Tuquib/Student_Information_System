<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // If user is admin, redirect to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }
        
        // Get student record and check if it exists
        $student = $user->student;
        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'No student record found. Please contact administrator.');
        }

        // Get enrollments only if student exists
        $enrollments = $student->enrollments()
            ->with(['subject', 'grade'])
            ->get();

        return view('student.dashboard', compact('enrollments'));
    }
} 