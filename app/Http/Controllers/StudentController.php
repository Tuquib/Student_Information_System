<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view('students', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|string|unique:students',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students',
                'course' => 'required|string',
                'year' => 'required',
            ]);

            $student = Student::create([
                'student_id' => (string)$request->student_id,
                'name' => $request->name,
                'email' => $request->email,
                'course' => $request->course,
                'year' => $request->year,
                'status' => 'Unenrolled'
            ]);

            return redirect()->route('students.index')
                ->with('success', 'Student added successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage());
            return back()->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        \Log::info('Update method called');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request data: ' . json_encode($request->all()));
        
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($student->profile_image) {
                Storage::delete(str_replace('/storage', 'public', $student->profile_image));
            }
            
            $path = $request->file('profile_image')->store('public/students');
            $data['profile_image'] = Storage::url($path);
        }

        $student->update($data);
        
        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Check if student is enrolled
        if ($student->enrollments()->exists()) {
            return redirect()->route('students.index')
                ->with('error', 'Cannot delete student. Student is currently enrolled in subjects.');
        }

        try {
            // Delete profile image if exists
            if ($student->profile_image) {
                Storage::delete('public/profile_images/' . $student->profile_image);
            }
            
            $student->delete();
            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('students.index')
                ->with('error', 'Error deleting student. Please try again.');
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $students = Student::where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->get(['id', 'name', 'email']);
        
        return response()->json($students);
    }
}
