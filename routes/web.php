<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AcademicSemesterController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Lecturer\LecturerDashboardController;
use App\Http\Controllers\Student\StudentDashboardController;


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
    Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'role:student']) //'auth', 'role:student'
    ->group(function () {

           // ================= DASHBOARD =================
        Route::get('/dashboard', [StudentDashboardController::class, 'index']) ->name('dashboard');


    });

/*
|--------------------------------------------------------------------------
| LECTURER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('lecturer')
    ->name('lecturer.')
    ->middleware(['auth', 'role:lecturer']) //'auth', 'role:lecturer'
    ->group(function () {

        Route::get('/dashboard', [LecturerDashboardController::class, 'index']) ->name('dashboard');
    });


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::name('admin.')
    ->middleware(['auth', 'role:admin']) //'auth', 'role:admin'
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
        Route::get('/departments/list/{faculty}',[DepartmentController::class, 'getByFaculty'])->name('departments.by-faculty');
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

                // ================= ACADEMIC SEMESTER =================
        Route::get('/academic-semester', [AcademicSemesterController::class, 'index'])->name('academic-semester.index');
        Route::post('/academic-semester', [AcademicSemesterController::class, 'store'])->name('academic-semester.store');
        Route::get('/academic-semester/{academicSemester}', [AcademicSemesterController::class, 'show'])->name('academic-semester.show');
        Route::get('/academic-semester/{academicSemester}/edit', [AcademicSemesterController::class, 'edit'])->name('academic-semester.edit');
        Route::put('/academic-semester/{academicSemester}', [AcademicSemesterController::class, 'update'])->name('academic-semester.update');
        Route::delete('/academic-semester/{academicSemester}', [AcademicSemesterController::class, 'destroy'])->name('academic-semester.destroy');

        // ================= LECTURERS =================
        Route::get('/lecturers', [LecturerController::class, 'index'])->name('lecturers.index');
        Route::post('/lecturers', [LecturerController::class, 'store'])->name('lecturers.store');
        Route::get('/lecturers/view/{lecturer}', [LecturerController::class, 'show'])->name('lecturers.show');
        Route::get('/lecturers/{lecturer}/edit', [LecturerController::class, 'edit'])->name('lecturers.edit');
        Route::put('/lecturers/{lecturer}', [LecturerController::class, 'update'])->name('lecturers.update');
        Route::delete('/lecturers/{lecturer}', [LecturerController::class, 'destroy'])->name('lecturers.destroy');
        Route::post('/lecturers/{lecturer}/activate', [LecturerController::class, 'activate'])->name('lecturers.activate');
        Route::post('/lecturers/{lecturer}/deactivate', [LecturerController::class, 'deactivate'])->name('lecturers.deactivate');

        // ================= PROGRAMMES =================
        Route::get('/programmes', [ProgrammeController::class, 'index'])->name('programmes.index');
        Route::get('/programmes/create', [ProgrammeController::class, 'create'])->name('programmes.create');
        Route::post('/programmes', [ProgrammeController::class, 'store'])->name('programmes.store');
        Route::get('/programmes/custom-settings', [ProgrammeController::class, 'customSettings'])->name('programmes.custom-settings');
        Route::put('/programmes/{programme}/custom-settings',[ProgrammeController::class, 'updateCustomSettings'])->name('programmes.updateCustomSettings');
        Route::get('/programmes/{programme}', [ProgrammeController::class, 'show'])->name('programmes.show');
        Route::get('/programmes/{programme}/edit', [ProgrammeController::class, 'edit'])->name('programmes.edit');
        Route::put('/programmes/{programme}', [ProgrammeController::class, 'update'])->name('programmes.update');
        Route::delete('/programmes/{programme}', [ProgrammeController::class, 'destroy'])->name('programmes.destroy');



        // ================= LEVELS =================
        Route::get('/levels', [LevelController::class, 'index'])->name('levels.index');
        Route::get('/levels/create', [LevelController::class, 'create'])->name('levels.create');
        Route::post('/levels', [LevelController::class, 'store'])->name('levels.store');
        Route::get('/levels/{level}', [LevelController::class, 'show'])->name('levels.show');
        Route::get('/levels/{level}/edit', [LevelController::class, 'edit'])->name('levels.edit');
        Route::put('/levels/{level}', [LevelController::class, 'update'])->name('levels.update');
        Route::delete('/levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy');

        // ================= COURSES =================
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');


        // ================= USERS =================
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


        // ================= STUDENTS =================
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/students/import', [StudentController::class, 'importStudents'])->name('students.import');
        Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
        Route::post('/students/{student}/promote', [StudentController::class, 'promote'])->name('students.promote');
        Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    });



