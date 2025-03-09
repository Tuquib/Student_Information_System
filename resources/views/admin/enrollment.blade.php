@extends('layouts.dashboardLayout')

@section('title')
Enrollments
@endsection

@section('content')
<div class="container-fluid py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Enrollments</h6>
                        <button type="button" class="btn btn-sm btn-success mx-3" data-bs-toggle="modal" data-bs-target="#addEnrollmentModal">
                            Add Enrollment
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Program</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subjects</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Units</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Semester</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">School Year</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $studentId => $studentEnrollments)
                                @php
                                    $firstEnrollment = $studentEnrollments->first();
                                    $student = $firstEnrollment->student;
                                @endphp
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-3">{{ $student->student_id }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $student->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $student->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $student->course }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $student->year }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @foreach($studentEnrollments as $enrollment)
                                                <div class="mb-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $enrollment->subject->name }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $enrollment->subject->code }} ({{ $enrollment->subject->units }} units)</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $studentEnrollments->sum(function($e) { return $e->subject->units; }) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">{{ $firstEnrollment->semester }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $firstEnrollment->school_year }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-link text-secondary mb-0"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editEnrollmentModal{{ $student->id }}">
                                            <i class="fa fa-edit text-xs"></i>
                                            Edit
                                        </button>
                                        <form action="{{ route('enrollments.destroy-all', $student->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient mb-0" 
                                                    onclick="return confirm('Are you sure? This will remove all subjects for this student.')">
                                                <i class="fa fa-trash text-xs"></i>
                                                Delete All
                                            </button>
                                        </form>
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

    <!-- Add Enrollment Modal -->
    <div class="modal fade" id="addEnrollmentModal" tabindex="-1" aria-labelledby="addEnrollmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEnrollmentModalLabel">Add New Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('enrollments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select class="form-control select2" 
                                    id="student_id" 
                                    name="student_id" 
                                    required>
                                <option value="">Select a student...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject_ids" class="form-label">Subjects</label>
                            <select class="form-control select2-multiple" 
                                    id="subject_ids" 
                                    name="subject_ids[]" 
                                    multiple
                                    required>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" data-units="{{ $subject->units }}">
                                        {{ $subject->name }} ({{ $subject->code }}) - {{ $subject->units }} units
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Units: <span class="total-units">0</span></label>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2" 
                                    style="border-color: #d2d6dc;"
                                    id="semester" 
                                    name="semester" 
                                    required>
                                <option value="">Select semester...</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="school_year" class="form-label">School Year</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2" 
                                   style="border-color: #d2d6dc;"
                                   id="school_year" 
                                   name="school_year" 
                                   placeholder="Enter school year"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Enrollment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($enrollments as $studentId => $studentEnrollments)
    @php
        $student = $studentEnrollments->first()->student;
    @endphp
    <div class="modal fade" id="editEnrollmentModal{{ $student->id }}" tabindex="-1" aria-labelledby="editEnrollmentModalLabel{{ $student->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEnrollmentModalLabel{{ $student->id }}">Edit Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('enrollments.update-all', $student->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id{{ $student->id }}" class="form-label">Student</label>
                            <select class="form-control select2" 
                                    id="student_id{{ $student->id }}" 
                                    name="student_id" 
                                    required>
                                <option value="{{ $student->id }}" selected>
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject_ids{{ $student->id }}" class="form-label">Subjects</label>
                            <select class="form-control select2-multiple" 
                                    id="subject_ids{{ $student->id }}" 
                                    name="subject_ids[]" 
                                    multiple
                                    required>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                            data-units="{{ $subject->units }}"
                                            {{ $studentEnrollments->contains('subject_id', $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }}) - {{ $subject->units }} units
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Units: <span class="total-units">
                                {{ $studentEnrollments->sum(function($e) { return $e->subject->units; }) }}
                            </span></label>
                        </div>
                        <div class="mb-3">
                            <label for="semester{{ $student->id }}" class="form-label">Semester</label>
                            <select class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2" 
                                    style="border-color: #d2d6dc;"
                                    id="semester{{ $student->id }}" 
                                    name="semester" 
                                    required>
                                <option value="1st Semester" {{ $studentEnrollments->contains('semester', '1st Semester') ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd Semester" {{ $studentEnrollments->contains('semester', '2nd Semester') ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer" {{ $studentEnrollments->contains('semester', 'Summer') ? 'selected' : '' }}>Summer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="school_year{{ $student->id }}" class="form-label">School Year</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2" 
                                   style="border-color: #d2d6dc;"
                                   id="school_year{{ $student->id }}" 
                                   name="school_year" 
                                   value="{{ $studentEnrollments->first()->school_year }}"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Enrollment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <footer class="footer py-4">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-sm text-muted text-lg-start">
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        made with <i class="fa fa-heart"></i> by
                        <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                        for a better web.
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for add modal
    $('#student_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#addEnrollmentModal'),
        placeholder: 'Type student name...',
        allowClear: true,
        minimumInputLength: 1,
        tags: false, // Don't create new options
        width: '100%',
        ajax: {
            url: '{{ route("students.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(student) {
                        return {
                            id: student.id,
                            text: student.name,
                            email: student.email
                        };
                    })
                };
            },
            cache: true
        },
        templateResult: formatStudent,
        templateSelection: formatStudent
    });

    // Initialize Select2 for edit modals
    $('.select2').each(function() {
        $(this).select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this).closest('.modal'),
            placeholder: 'Type student name...',
            allowClear: true,
            minimumInputLength: 1,
            tags: false,
            width: '100%',
            ajax: {
                url: '{{ route("students.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(student) {
                            return {
                                id: student.id,
                                text: student.name,
                                email: student.email
                            };
                        })
                    };
                },
                cache: true
            },
            templateResult: formatStudent,
            templateSelection: formatStudent
        });
    });

    // Initialize Select2 for multiple subjects
    $('.select2-multiple').each(function() {
        $(this).select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this).closest('.modal'),
            width: '100%'
        });
    });

    // Calculate total units when subjects are selected/deselected
    $('.select2-multiple').on('change', function() {
        let totalUnits = 0;
        $(this).find(':selected').each(function() {
            totalUnits += parseInt($(this).data('units'));
        });
        
        const modalId = $(this).closest('.modal').attr('id');
        $(`#${modalId}`).find('.total-units').text(totalUnits);
        
        if (totalUnits > 24) {
            alert('Warning: Total units exceed 24!');
            $(this).val(null).trigger('change');
        }
    });

    function formatStudent(student) {
        if (!student.id) return student.text;
        
        return $('<div class="d-flex align-items-center">' +
            '<div class="ms-2">' +
                '<div class="font-weight-bold">' + student.text + '</div>' +
                '<div class="small text-muted">' + student.email + '</div>' +
            '</div>' +
        '</div>');
    }
});
</script>
@endpush
@endsection