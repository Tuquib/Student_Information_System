@extends('layouts.dashboardLayout')

@section('title')
Grades
@endsection

@section('content')
<div class="container-fluid py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Student Grades</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Units</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Grade</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remarks</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments->groupBy('student_id') as $studentId => $studentEnrollments)
                                    @foreach($studentEnrollments as $index => $enrollment)
                                        <tr>
                                            @if($index === 0)
                                                <td rowspan="{{ $studentEnrollments->count() }}" class="align-middle">
                                                    <p class="text-xs font-weight-bold mb-0 px-3">{{ $enrollment->student->student_id }}</p>
                                                </td>
                                                <td rowspan="{{ $studentEnrollments->count() }}" class="align-middle">
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $enrollment->student->name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $enrollment->student->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $enrollment->subject->name }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $enrollment->subject->code }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $enrollment->subject->units }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $enrollment->grade ? $enrollment->grade->grade : 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm {{ $enrollment->grade && $enrollment->grade->grade <= 3.00 ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                                    {{ $enrollment->grade ? $enrollment->grade->remarks : 'No Grade' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-link text-secondary mb-0"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editGradeModal{{ $enrollment->id }}">
                                                    <i class="fa fa-edit text-xs"></i>
                                                    {{ $enrollment->grade ? 'Edit' : 'Add' }} Grade
                                                </button>
                                                @if($enrollment->grade)
                                                    <form action="{{ route('grades.destroy', $enrollment->grade->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this grade?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger mb-0">
                                                            <i class="fa fa-trash text-xs"></i>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Add a total units and GWA row for each student -->
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-end pe-3">
                                            <strong class="text-xs">Total Units:</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong class="text-xs">{{ $studentEnrollments->sum(function($e) { return $e->subject->units; }) }}</strong>
                                        </td>
                                        <td class="text-center" colspan="3">
                                            <strong class="text-xs">
                                                GWA: {{ number_format($studentEnrollments->avg(function($e) { 
                                                    return $e->grade ? $e->grade->grade : 0; 
                                                }), 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Modals -->
    @foreach($enrollments as $enrollment)
    <div class="modal fade" id="editGradeModal{{ $enrollment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $enrollment->grade ? 'Edit' : 'Add' }} Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('grades.update', $enrollment->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <p><strong>Student:</strong> {{ $enrollment->student->name }}</p>
                            <p><strong>Subject:</strong> {{ $enrollment->subject->name }} ({{ $enrollment->subject->code }})</p>
                        </div>
                        <div class="mb-3">
                            <label for="grade{{ $enrollment->id }}" class="form-label">Grade</label>
                            <select class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" id="grade{{ $enrollment->id }}" name="grade" required>
                                <option value="">Select Grade</option>
                                @foreach(App\Models\Grade::getGradeOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ $enrollment->grade && $enrollment->grade->grade == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Grade Modal -->
    <div class="modal fade" id="addGradeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select class="form-control select2" id="student_id" required>
                                <option value="">Select Student</option>
                                @foreach($enrollments->groupBy('student_id') as $studentId => $studentEnrollments)
                                    @php $student = $studentEnrollments->first()->student; @endphp
                                    <option value="{{ $student->id }}">
                                        {{ $student->student_id }} - {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="enrollment_id" class="form-label">Subject</label>
                            <select class="form-control" id="enrollment_id" name="enrollment_id" required disabled>
                                <option value="">Select Subject First</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade</label>
                            <select class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" id="grade" name="grade" required>
                                <option value="">Select Grade</option>
                                @foreach(App\Models\Grade::getGradeOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for student selection
    $('#student_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#addGradeModal'),
        placeholder: 'Select Student',
        allowClear: true,
        width: '100%'
    });

    // Store enrollments data
    const enrollments = @json($enrollments->groupBy('student_id'));

    // When student is selected, populate subjects dropdown
    $('#student_id').on('change', function() {
        const studentId = $(this).val();
        const $subjectSelect = $('#enrollment_id');
        
        $subjectSelect.empty().append('<option value="">Select Subject</option>');
        
        if (studentId) {
            const studentEnrollments = enrollments[studentId];
            
            studentEnrollments.forEach(enrollment => {
                if (!enrollment.grade) {  // Only show subjects without grades
                    $subjectSelect.append(`
                        <option value="${enrollment.id}">
                            ${enrollment.subject.name} (${enrollment.subject.code})
                        </option>
                    `);
                }
            });
            
            $subjectSelect.prop('disabled', false);
        } else {
            $subjectSelect.prop('disabled', true);
        }
    });
});
</script>
@endpush
@endsection