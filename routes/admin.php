<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomAuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TeachersController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\MockTestController;


Route::get('/', [CustomAuthController::class, 'index'])->name('login');
Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::get('/', [CustomAuthController::class, 'index'])->name('login');
    Route::get('login', [CustomAuthController::class, 'index'])->name('login');
    Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');  
    Route::get('logout', [CustomAuthController::class, 'signOut'])->name('logout');
    
    Route::group(['middleware' => ['auth','admin']], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard-counts', [HomeController::class, 'dashboardCounts'])->name('dashboard-counts');
        Route::get('/state/{id?}', [HomeController::class, 'getCountryStates'])->name('country.states');
        Route::get('/packages/{id?}', [HomeController::class, 'getCoursePackages'])->name('course.packages');

        /* Courses section*/
        Route::get('/all-courses', [HomeController::class, 'getAllCourses'])->name('all-courses');
        Route::get('/course/create', [HomeController::class, 'createCourse'])->name('course.create');
        Route::post('/course/store', [HomeController::class, 'storeCourse'])->name('course.store');
        Route::get('/course/edit/{id}', [HomeController::class, 'editCourse'])->name('course.edit');
        Route::post('/course/update/{id}', [HomeController::class, 'updateCourse'])->name('course.update');
        Route::post('/course/delete/', [HomeController::class, 'deleteCourse'])->name('course.delete');
        Route::get('/course/divisions', [HomeController::class, 'getCourseDivisions'])->name('course.divisions');

        /* Course Divisions section*/
        Route::get('/all-divisions', [HomeController::class, 'getAllDivisions'])->name('all-divisions');
        Route::get('/division/create', [HomeController::class, 'createDivision'])->name('division.create');
        Route::post('/division/store', [HomeController::class, 'storeDivision'])->name('division.store');
        Route::get('/division/edit/{id}', [HomeController::class, 'editDivision'])->name('division.edit');
        Route::post('/division/update/{id}', [HomeController::class, 'updateDivision'])->name('division.update');
        Route::post('/division/delete/', [HomeController::class, 'deleteDivision'])->name('division.delete');
        
        Route::get('/course-packages', [HomeController::class, 'getAllCoursePackages'])->name('course-packages');
        // Route::get('/packages/create', [HomeController::class, 'createPackages'])->name('packages.create');
        Route::get('/package/create', [HomeController::class, 'createPackage'])->name('package.create');
        Route::post('/packages/store', [HomeController::class, 'storePackage'])->name('packages.store');
        Route::get('/packages/edit/{id}', [HomeController::class, 'editPackage'])->name('packages.edit');
        Route::post('/packages/update/{id}', [HomeController::class, 'updatePackage'])->name('packages.update');
        Route::post('/packages/delete/', [HomeController::class, 'deletePackage'])->name('packages.delete');

        Route::get('/classes', [HomeController::class, 'getAllClasses'])->name('classes');
        Route::get('/class/create', [HomeController::class, 'createClass'])->name('class.create');
        Route::post('/class/store', [HomeController::class, 'storeClass'])->name('class.store');
        Route::get('/class/edit/{id}', [HomeController::class, 'editClass'])->name('class.edit');
        Route::post('/class/update/{id}', [HomeController::class, 'updateClass'])->name('class.update');
        Route::post('/class/delete/', [HomeController::class, 'deleteClass'])->name('class.delete');
        Route::get('/course/pack-divisions', [HomeController::class, 'getCourseDivisionsPackages'])->name('course.pack-divisions');

        Route::get('/teachers', [TeachersController::class, 'getAllTeachers'])->name('teachers');
        Route::get('/teacher/create', [TeachersController::class, 'createTeacher'])->name('teacher.create');
        Route::post('/teacher/store', [TeachersController::class, 'storeTeacher'])->name('teacher.store');
        Route::get('/teacher/edit/{id}', [TeachersController::class, 'editTeacher'])->name('teacher.edit');
        Route::post('/teacher/update/{id}', [TeachersController::class, 'updateTeacher'])->name('teacher.update');
        Route::post('/teacher/delete/', [TeachersController::class, 'deleteTeacher'])->name('teacher.delete');
        Route::get('/teacher/divisions', [TeachersController::class, 'getTeacherDivisions'])->name('teacher.divisions');
        Route::get('/teacher/divisions/filter', [TeachersController::class, 'getTeacherDivisionsFilter'])->name('teacher.divisions.filter');

        Route::get('/assign-teachers', [TeachersController::class, 'getAllAssignedTeachers'])->name('assign-teachers');
        Route::get('/assign-teacher/create', [TeachersController::class, 'createAssign'])->name('assign-teacher.create');
        Route::post('/assign-teacher/store', [TeachersController::class, 'storeAssign'])->name('assign-teacher.store');
        Route::get('/assign-teacher/edit/{id}', [TeachersController::class, 'editAssign'])->name('assign-teacher.edit');
        Route::post('/assign-teacher/update/{id}', [TeachersController::class, 'updateAssign'])->name('assign-teacher.update');
        Route::post('/assign-teacher/delete/', [TeachersController::class, 'deleteAssign'])->name('assign-teacher.delete');
        Route::post('/assign-teacher/cancel', [TeachersController::class, 'cancelBooking'])->name('assign-teacher.cancel');

        Route::get('/students', [StudentController::class, 'getAllStudents'])->name('students');
        Route::get('/student/create', [StudentController::class, 'createStudent'])->name('student.create');
        Route::post('/student/store', [StudentController::class, 'storeStudent'])->name('student.store');
        Route::get('/student/edit/{id}', [StudentController::class, 'editStudent'])->name('student.edit');
        Route::post('/student/update/{id}', [StudentController::class, 'updateStudent'])->name('student.update');
        Route::post('/student/delete/', [StudentController::class, 'deleteStudent'])->name('student.delete');
        Route::post('/student/approve/', [StudentController::class, 'approveStudent'])->name('student.approve');
        Route::get('/bulk-student/create', [StudentController::class, 'createBulkStudent'])->name('student.bulk-create');
        Route::post('/student/bulk-store', [StudentController::class, 'storeBulkStudent'])->name('student.bulk-store');
        Route::get('/view-student', [StudentController::class, 'viewStudent'])->name('view-student');

        Route::get('/student-bookings', [StudentController::class, 'getAllStudentBookings'])->name('student.bookings');
        Route::post('/booking/cancel/', [StudentController::class, 'cancelBooking'])->name('booking.cancel');
        Route::get('/booking/create', [StudentController::class, 'createBooking'])->name('booking.create');
        Route::post('/booking/store', [StudentController::class, 'storeBooking'])->name('booking.store');
        Route::get('/student/divisions', [StudentController::class, 'getStudentDivisions'])->name('student.divisions');
        Route::get('/get-slots', [StudentController::class, 'getTimeSlots'])->name('get-slots');
        Route::get('/get-available-teachers', [StudentController::class, 'getAvailableTeachers'])->name('get-available-teachers');
        Route::get('/remarks', [StudentController::class, 'remarks'])->name('remarks');
        Route::post('/booking/attend/', [StudentController::class, 'attendBooking'])->name('booking.attend');
        Route::get('/bookings-export', [StudentController::class, 'exportBooking'])->name('export.bookings');

        Route::get('/attendance/list', [StudentController::class, 'getAttendanceList'])->name('attendance-list');
        Route::get('/view-attendance/{id}', [StudentController::class, 'getStudentsAttendanceList'])->name('view-attendance');
        Route::get('/edit-attendance/{id}', [StudentController::class, 'getStudentsEditList'])->name('edit-attendance');
        Route::get('/attendance', [StudentController::class, 'getAttendance'])->name('attendance');
        Route::get('/course/classes', [StudentController::class, 'getCourseClasses'])->name('course.classes');
        Route::get('/students-list', [StudentController::class, 'getStudentsList'])->name('students-list');
        Route::post('/save/attendance', [StudentController::class, 'saveAttendance'])->name('save-attendance');
        Route::post('/update/attendance', [StudentController::class, 'updateAttendance'])->name('update.attendance');


        Route::get('/mock-tests', [MockTestController::class, 'getMockTestList'])->name('mock-tests');
        Route::get('/mock/create', [MockTestController::class, 'createMockTest'])->name('mock.create');
        Route::post('/mock/store', [MockTestController::class, 'storeMockTest'])->name('mock.store');
        Route::get('/mock/edit/{id}', [MockTestController::class, 'editMockTest'])->name('mock.edit');
        Route::post('/mock/update/{id}', [MockTestController::class, 'updateMockTest'])->name('mock.update');
        Route::post('/mock/delete/', [MockTestController::class, 'deleteMockTest'])->name('mock.delete');

        Route::get('/bulk-mock/create', [MockTestController::class, 'createBulkMockTest'])->name('mock.bulk-create');
        Route::post('/mock/bulk-store', [MockTestController::class, 'storeBulkMockTest'])->name('mock.bulk-store');
    });

});