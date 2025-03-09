@extends('layouts.dashboardLayout')

@section('title')
My Grades
@endsection

@section('content')
<div class="container-fluid py-2">
    <!-- Student Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Student Information</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-0">Name: <span class="text-secondary">{{ $student->name }}</span></h6>
                            <h6 class="mb-0">Email: <span class="text-secondary">{{ $student->email }}</span></h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-0">Course: <span class="text-secondary">{{ $student->course }}</span></h6>
                            <h6 class="mb-0">Year Level: <span class="text-secondary">{{ $student->year}}</span></h6>
                            <h6 class="mb-0">GWA: <span class="text-secondary font-weight-bold">{{ $gwa }}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">My Grades</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Units</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Grade</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remarks</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Semester</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">School Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $enrollment->subject->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $enrollment->subject->code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $enrollment->subject->units }}</p>
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
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $enrollment->semester }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $enrollment->school_year }}</span>
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
</div>
@endsection