<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentGradesController extends Controller
{
    public function index()
    {
        $student = auth()->guard('student')->user()->student;
        $enrollments = $student->enrollments()
            ->with(['subject', 'grade'])
            ->get();

        // Calculate GWA
        $totalUnits = 0;
        $totalGradePoints = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade) {
                $totalUnits += $enrollment->subject->units;
                $totalGradePoints += ($enrollment->grade->grade * $enrollment->subject->units);
            }
        }

        $gwa = $totalUnits > 0 ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

        return view('student.grades', compact('enrollments', 'student', 'gwa'));
    }
} 