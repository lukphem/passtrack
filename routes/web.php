<?php

use App\Http\Controllers\AcademicSemesterController;
use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\Lecturer\LecturerDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;





Route::prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware([])
    ->group(function () {



        // ================= DASHBOARD =================
        Route::get('/dashboard', [DashboardController::class, 'index']) ->name('dashboard');

        // ================= FACULTIES =================
        Route::get('/faculties', [FacultyController::class, 'index'])->name('faculties.index');
        Route::get('/faculties/create', [FacultyController::class, 'create'])->name('faculties.create');
        Route::post('/faculties', [FacultyController::class, 'store'])->name('faculties.store');
        Route::get('/faculties/{faculty}', [FacultyController::class, 'show'])->name('faculties.show');
        Route::get('/faculties/{faculty}/edit', [FacultyController::class, 'edit'])->name('faculties.edit');
        Route::put('/faculties/{faculty}', [FacultyController::class, 'update'])->name('faculties.update');
        Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy'])->name('faculties.destroy');

        // ================= DEPARTMENTS =================
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        // ================= ACADEMIC SESSIONS =================
        Route::get('/academic-sessions', [AcademicSessionController::class, 'index'])->name('academic-sessions.index');
        Route::get('/academic-sessions/create', [AcademicSessionController::class, 'create'])->name('academic-sessions.create');
        Route::post('/academic-sessions', [AcademicSessionController::class, 'store'])->name('academic-sessions.store');
        Route::get('/academic-sessions/{academicSession}', [AcademicSessionController::class, 'show'])->name('academic-sessions.show');
        Route::get('/academic-sessions/{academicSession}/edit', [AcademicSessionController::class, 'edit'])->name('academic-sessions.edit');
        Route::put('/academic-sessions/{academicSession}', [AcademicSessionController::class, 'update'])->name('academic-sessions.update');
        Route::delete('/academic-sessions/{academicSession}', [AcademicSessionController::class, 'destroy'])->name('academic-sessions.destroy');
        Route::post('/academic-sessions/{academicSession}/activate', [AcademicSessionController::class, 'activate'])->name('academic-sessions.activate');


        // ================= COURSES =================
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

                // ================= ACADEMIC SEMESTER =================
        Route::get('/academic-semester', [AcademicSemesterController::class, 'index'])->name('academic-semester.index');
        Route::get('/academic-semester/create', [AcademicSemesterController::class, 'create'])->name('academic-semester.create');
        Route::post('/academic-semester', [AcademicSemesterController::class, 'store'])->name('academic-semester.store');
        Route::get('/academic-semester/{academicSemester}', [AcademicSemesterController::class, 'show'])->name('academic-semester.show');
        Route::get('/academic-semester/{academicSemester}/edit', [AcademicSemesterController::class, 'edit'])->name('academic-semester.edit');
        Route::put('/academic-semester/{academicSemester}', [AcademicSemesterController::class, 'update'])->name('academic-semester.update');
        Route::delete('/academic-semester/{academicSemester}', [AcademicSemesterController::class, 'destroy'])->name('academic-semester.destroy');



        // ================= USERS =================
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    });

