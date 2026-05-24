<?php


use App\Http\Controllers\Admin\AcademicSemesterController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\AdminCourseRegistrationController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Lecturer\AssignmentController;
use App\Http\Controllers\Lecturer\LecturerCourseController;
use App\Http\Controllers\Lecturer\LecturerDashboardController;
use App\Http\Controllers\Lecturer\LecturerStudentController;
use App\Http\Controllers\Lecturer\MaterialController;
use App\Http\Controllers\Lecturer\QuizController;
use App\Http\Controllers\Student\StudentCourseRegistrationController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentMaterialController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('portal/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('portal/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
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

            // ================= COURSE REGISTRATION =================
        Route::get('/course-registrations', [StudentCourseRegistrationController::class, 'index'])->name('courses.index');
        Route::get('/registration-status', [StudentCourseRegistrationController::class, 'registrationStatus'])->name('registration.status');
        Route::get('/available-courses', [StudentCourseRegistrationController::class, 'availableCourses'])->name('courses.available');
        Route::get('/my-courses', [StudentCourseRegistrationController::class, 'myCourses'])->name('courses.mine');
        Route::get('/register-courses', [StudentCourseRegistrationController::class, 'registerCourses'])->name('courses.register.index');
        Route::post('/register-courses', [StudentCourseRegistrationController::class, 'registerCourses'])->name('courses.register');
        Route::delete('/drop-course/{course_id}', [StudentCourseRegistrationController::class, 'dropCourse'])->name('courses.drop');
        Route::get('/available-courses/expand', [StudentCourseRegistrationController::class, 'expandCourses'])->name('courses.expand');
        Route::get('/student/courses/print', [StudentCourseRegistrationController::class, 'printCourses'])->name('courses.print');

        // ================= LEARNING MATERIALS (LMS) =================
        Route::get('/learning-materials',[StudentMaterialController::class, 'index'])->name('materials.index');
        Route::get('/learning-materials/{id}/view', [StudentMaterialController::class, 'show'])->name('material.view');
        Route::post('/learning-materials/{id}/track', [StudentMaterialController::class, 'track'])->name('material.track');
    });

/*
|--------------------------------------------------------------------------
| LECTURER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('lecturer')
    ->name('lecturer.')
    ->middleware(['auth', 'role:lecturer'])
    ->group(function () {

        Route::get('/dashboard', [LecturerDashboardController::class, 'index']) ->name('dashboard');
        // ================= COURSES =================
        Route::get('/courses', [LecturerCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}/students', [LecturerCourseController::class, 'students'])->name('courses.students');
        Route::get('/courses/{course}/students/export',[LecturerCourseController::class, 'export'])->name('courses.students.export');
        // ================= STUDENTS (GLOBAL VIEW) =================
        Route::get('/students', [LecturerStudentController::class, 'index'])->name('students.index');
        Route::get('/students/export', [LecturerStudentController::class, 'export'])->name('students.export');

        // ================= MATERIALS =================
        Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
        Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
        Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

        // ================= ASSIGNMENTS =================
        Route::get('/courses/{course}/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/courses/{course}/assignments', [AssignmentController::class, 'store']) ->name('assignments.store');
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');

        // ================= QUIZ (AI READY) =================
        Route::get('/courses/{course}/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
        Route::post('/courses/{course}/quizzes/generate-ai', [QuizController::class, 'generateAI'])->name('quizzes.generate.ai');
        Route::post('/courses/{course}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');

        });


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::name('admin.')
    ->middleware([]) //'auth', 'role:admin'
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


        // ================= COURSE REGISTRATION OVERRIDE =================
        Route::get('course-registration', [AdminCourseRegistrationController::class, 'index'])->name('course-registration.index');
        Route::get('/course-registration/{student}', [AdminCourseRegistrationController::class, 'manageStudent'])->name('course-registration.manage');
        Route::post('/course-registration/{student}/add', [AdminCourseRegistrationController::class, 'registerCourses'])->name('course-registration.addcourse');
        Route::delete('/course-registration/{student}/course/{course}',[AdminCourseRegistrationController::class, 'dropCourse'])->name('course-registration.dropcourse');
        Route::get('/course-registration/{student}/print', [AdminCourseRegistrationController::class, 'printCourses'])->name('course-registration.print');
 });



