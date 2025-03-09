<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'subject', 'grade'])
            ->whereHas('student', function($query) {
                $query->where('status', 'Enrolled');
            })
            ->get();
        return view('admin.grades', compact('enrollments'));
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
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grade' => 'required|numeric|between:1.00,5.00',
        ]);

        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        
        // Check if grade already exists
        if ($enrollment->grade) {
            return redirect()->back()
                ->with('error', 'Grade already exists for this enrollment');
        }

        $enrollment->grade()->create([
            'grade' => $request->grade,
            'remarks' => $this->getDefaultRemarks($request->grade)
        ]);

        return redirect()->route('grades.index')
            ->with('success', 'Grade added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $enrollmentId)
    {
        $request->validate([
            'grade' => 'required|numeric|between:1.00,5.00',
        ]);

        $enrollment = Enrollment::findOrFail($enrollmentId);
        
        $enrollment->grade()->updateOrCreate(
            ['enrollment_id' => $enrollmentId],
            [
                'grade' => $request->grade,
                'remarks' => $this->getDefaultRemarks($request->grade)
            ]
        );

        return redirect()->route('grades.index')
            ->with('success', 'Grade updated successfully');
    }

    private function getDefaultRemarks($grade)
    {
        return $grade <= 3.00 ? 'Passed' : 'Failed';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return redirect()->route('grades.index')
                ->with('success', 'Grade deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting grade: ' . $e->getMessage());
        }
    }
}
