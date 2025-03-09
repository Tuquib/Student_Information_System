@extends('layouts.dashboardLayout')

@section('title')
Students
@endsection

@section('content')
<div class="container-fluid py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                        <h6 class="text-white text-capitalize ps-3">Students table</h6>
                        <button type="button" class="btn btn-sm btn-success mx-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                            Add Student
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Course</th>
                                    <th class="tex-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Year</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gender</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-3">{{ $student->student_id }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $student->profile_image ?? '../assets/img/team-2.jpg' }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $student->name }}">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $student->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $student->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $student->course }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">{{ $student->year }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $student->gender }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $student->status == 'Enrolled' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-link text-secondary mb-0"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editStudentModal{{ $student->id }}">
                                            <i class="fa fa-edit t  ext-xs"></i>
                                            Edit
                                        </button>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient mb-0" 
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="fa fa-trash text-xs"></i>
                                                Delete
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

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('students.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="input-group input-group-static mb-4">
                            <label>Student ID</label>
                            <input type="text" name="student_id" class="form-control" required>
                        </div>

                        <div class="input-group input-group-static mb-4">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="input-group input-group-static mb-4">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="input-group input-group-static mb-4">
                            <label>Course</label>
                            <input type="text" name="course" class="form-control" required>
                        </div>

                        <div class="input-group input-group-static mb-4">
                            <label>Year</label>
                            <input type="text" name="year" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($students as $student)
    <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="editStudentModalLabel{{ $student->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel{{ $student->id }}">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id{{ $student->id }}" class="form-label">Student ID</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="student_id{{ $student->id }}" 
                                   name="student_id" 
                                   value="{{ old('student_id', $student->student_id) }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="name{{ $student->id }}" class="form-label">Name</label>
                            <input type="number" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400 @error('name') is-invalid @enderror" 
                                   style="border-color: #d2d6dc;"
                                   id="name{{ $student->id }}" 
                                   name="name" 
                                   value="{{ old('name', $student->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email{{ $student->id }}" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="email{{ $student->id }}" 
                                   name="email" 
                                   value="{{ old('email', $student->email) }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="course{{ $student->id }}" class="form-label">Course</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="course{{ $student->id }}" 
                                   name="course" 
                                   value="{{ old('course', $student->course) }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="year{{ $student->id }}" class="form-label">Year</label>
                            <select class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                    style="border-color: #d2d6dc;"
                                    id="year{{ $student->id }}" 
                                    name="year" 
                                    required>
                                <option value="1st Year" {{ $student->year == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ $student->year == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ $student->year == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ $student->year == '4th Year' ? 'selected' : '' }}>4th Year</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gender{{ $student->id }}" class="form-label">Gender</label>
                            <select class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400"
                                    style="border-color: #d2d6dc;"
                                    id="gender{{ $student->id }}" 
                                    name="gender" 
                                    required>
                                <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $student->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="profile_image{{ $student->id }}" class="form-label">Profile Image</label>
                            <input type="file" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="profile_image{{ $student->id }}" 
                                   name="profile_image">
                            @if($student->profile_image)
                                <img src="{{ $student->profile_image }}" alt="Current profile image" class="mt-2" style="max-width: 100px;">
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    <footer class="footer py-4  ">
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
@endsection