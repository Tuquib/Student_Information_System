<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total counts
        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        $totalEnrollments = Enrollment::count();
        $totalGrades = Grade::count();

        // Get student status counts
        $studentStatus = Student::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        // Get enrolled and unenrolled counts
        $enrolledCount = $studentStatus['Enrolled'] ?? 0;
        $unenrolledCount = $studentStatus['Unenrolled'] ?? 0;

        // Get course distribution
        $courseDistribution = Student::select(
            'course',
            DB::raw('COUNT(*) as student_count')
        )
            ->whereNotNull('course')
            ->groupBy('course')
            ->orderBy('course')
            ->get();

        // Get recent data
        $recentStudents = Student::latest()->take(5)->get();
        $recentGrades = Grade::with(['enrollment.student', 'enrollment.subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'totalEnrollments',
            'totalGrades',
            'studentStatus',
            'enrolledCount',
            'unenrolledCount',
            'courseDistribution',
            'recentStudents',
            'recentGrades'
        ));
    }
} 