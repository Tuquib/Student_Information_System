<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentGradesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subjects', function () {
    return view('subjects');
})->name('subjects');

Route::get('/profiles', function () {
    return view('profiles');
})->name('profiles');

// Admin routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Student routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/grades', [StudentGradesController::class, 'index'])
        ->name('student.grades');
});

// Common routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    
    // Subject routes
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::patch('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    
    // Enrollment routes
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::patch('/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    Route::delete('/enrollments/student/{student}', [EnrollmentController::class, 'destroyAll'])
        ->name('enrollments.destroy-all');
    Route::patch('/enrollments/student/{student}', [EnrollmentController::class, 'updateAll'])->name('enrollments.update-all');
    
    // Grade routes with name prefix 'grades.'
    Route::group(['as' => 'grades.'], function () {
        Route::get('/grades', [GradeController::class, 'index'])->name('index');
        Route::post('/grades', [GradeController::class, 'store'])->name('store');
        Route::patch('/grades/{enrollment}', [GradeController::class, 'update'])->name('update');
        Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('destroy');
    });

    // Student Authentication Routes
    Route::get('/register', [StudentAuthController::class, 'showRegistrationForm'])
        ->name('student.register');
    Route::post('/register', [StudentAuthController::class, 'register']);
});

require __DIR__.'/auth.php';
