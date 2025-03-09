@extends('layouts.dashboardLayout')

@section('title')
Subjects
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
                        <h6 class="text-white text-capitalize ps-3">Subjects table</h6>
                        <button type="button" class="btn btn-sm btn-success mx-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                            Add Subject
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject Code</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Units</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $subject->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $subject->code }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $subject->units }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-link text-secondary mb-0"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editSubjectModal{{ $subject->id }}">
                                            <i class="fa fa-edit text-xs"></i>
                                            Edit
                                        </button>
                                        <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline">
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

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Subject Name</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="name" 
                                   name="name" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Subject Code</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="code" 
                                   name="code" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="units" class="form-label">Units</label>
                            <input type="number" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2" 
                                   style="border-color: #d2d6dc;"
                                   id="units" 
                                   name="units" 
                                   min="1"
                                   max="5"
                                   value="{{ old('units') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($subjects as $subject)
    <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectModalLabel{{ $subject->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubjectModalLabel{{ $subject->id }}">Edit Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('subjects.update', $subject) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name{{ $subject->id }}" class="form-label">Subject Name</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="name{{ $subject->id }}" 
                                   name="name" 
                                   value="{{ old('name', $subject->name) }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="code{{ $subject->id }}" class="form-label">Subject Code</label>
                            <input type="text" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-gray-400" 
                                   style="border-color: #d2d6dc;"
                                   id="code{{ $subject->id }}" 
                                   name="code" 
                                   value="{{ old('code', $subject->code) }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="units{{ $subject->id }}" class="form-label">Units</label>
                            <input type="number" 
                                   class="form-control border border-2 border-gray-300 rounded-lg px-3 py-2" 
                                   style="border-color: #d2d6dc;"
                                   id="units{{ $subject->id }}" 
                                   name="units" 
                                   min="1"
                                   max="5"
                                   value="{{ old('units', $subject->units) }}" 
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Subject</button>
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
@endsection