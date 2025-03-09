<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'subject'])
            ->get()
            ->groupBy('student_id');
        $students = Student::all();
        $subjects = Subject::all();
        return view('admin.enrollment', compact('enrollments', 'students', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        $student = Student::find($request->student_id);
        $subjectIds = $request->subject_ids;
        
        // Begin transaction to ensure all enrollments are created or none
        DB::beginTransaction();
        
        try {
            foreach ($subjectIds as $subjectId) {
                Enrollment::create([
                    'student_id' => $request->student_id,
                    'subject_id' => $subjectId,
                    'semester' => $request->semester,
                    'school_year' => $request->school_year
                ]);
            }
            
            // Update student status
            $student->updateEnrollmentStatus();
            
            DB::commit();
            
            return redirect()->route('enrollments.index')
                ->with('success', 'Enrollments created successfully');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error creating enrollments: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        // Store the old student to update their status later
        $oldStudent = $enrollment->student;
        
        // Update the enrollment
        $enrollment->update($request->validated());
        
        // Refresh the enrollment to get the new student relationship
        $enrollment->refresh();
        
        // Update status for both old and new student
        $oldStudent->updateEnrollmentStatus();
        if ($oldStudent->id !== $enrollment->student_id) {
            // Get fresh instance of new student and update status
            $newStudent = Student::find($enrollment->student_id);
            $newStudent->updateEnrollmentStatus();
        }
        
        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $student = $enrollment->student;
        $enrollment->delete();
        $student->updateEnrollmentStatus();
        
        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment deleted successfully');
    }

    public function destroyAll($studentId)
    {
        $student = Student::findOrFail($studentId);
        
        DB::beginTransaction();
        try {
            $student->enrollments()->delete();
            $student->updateEnrollmentStatus();
            DB::commit();
            
            return redirect()->route('enrollments.index')
                ->with('success', 'All enrollments deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error deleting enrollments: ' . $e->getMessage());
        }
    }

    public function updateAll(Request $request, $studentId)
    {
        try {
            // Validate request
            $request->validate([
                'subject_ids' => 'required|array',
                'subject_ids.*' => 'exists:subjects,id',
                'semester' => 'required|string',
                'school_year' => 'required|string'
            ]);

            // Check total units
            $totalUnits = Subject::whereIn('id', $request->subject_ids)
                ->sum('units');
            
            if ($totalUnits > 24) {
                return back()->with('error', 'Total units cannot exceed 24 units.');
            }

            // Check for duplicate subjects
            $existingEnrollments = Enrollment::where('student_id', $studentId)
                ->where('semester', $request->semester)
                ->where('school_year', $request->school_year)
                ->whereNotIn('subject_id', $request->subject_ids)
                ->exists();

            if ($existingEnrollments) {
                return back()->with('error', 'Student already enrolled in some subjects for this semester.');
            }

            DB::beginTransaction();

            // Update enrollments
            $student = Student::findOrFail($studentId);
            
            // Delete removed subjects
            $student->enrollments()
                ->where('semester', $request->semester)
                ->where('school_year', $request->school_year)
                ->whereNotIn('subject_id', $request->subject_ids)
                ->delete();

            // Add new subjects
            foreach ($request->subject_ids as $subjectId) {
                Enrollment::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'semester' => $request->semester,
                        'school_year' => $request->school_year,
                    ]
                );
            }

            $student->updateEnrollmentStatus();
            
            DB::commit();
            return redirect()->route('enrollments.index')
                ->with('success', 'Enrollment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating enrollment: ' . $e->getMessage());
        }
    }
}
