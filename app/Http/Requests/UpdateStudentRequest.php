<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|string|max:20|unique:students,student_id,' . $this->student->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $this->student->id,
            'course' => 'required|string|max:255',
            'year' => 'required|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'gender' => 'required|in:Male,Female,Other',
            'profile_image' => 'nullable|image|max:2048'
        ];
    }
}
