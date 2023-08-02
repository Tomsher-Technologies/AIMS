<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomAuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TeachersController;

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::get('/', [CustomAuthController::class, 'index'])->name('login');
    Route::get('login', [CustomAuthController::class, 'index'])->name('login');
    Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');  
    Route::get('logout', [CustomAuthController::class, 'signOut'])->name('logout');
    
    Route::group(['middleware' => ['auth','admin']], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

        /* Courses section*/
        Route::get('/all-courses', [HomeController::class, 'getAllCourses'])->name('all-courses');
        Route::get('/course/create', [HomeController::class, 'createCourse'])->name('course.create');
        Route::post('/course/store', [HomeController::class, 'storeCourse'])->name('course.store');
        Route::get('/course/edit/{id}', [HomeController::class, 'editCourse'])->name('course.edit');
        Route::post('/course/update/{id}', [HomeController::class, 'updateCourse'])->name('course.update');
        Route::post('/course/delete/', [HomeController::class, 'deleteCourse'])->name('course.delete');
        Route::get('/course/divisions', [HomeController::class, 'getCourseDivisions'])->name('course.divisions');
        
        Route::get('/course-packages', [HomeController::class, 'getAllCoursePackages'])->name('course-packages');
        Route::get('/packages/create', [HomeController::class, 'createPackage'])->name('packages.create');
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

        Route::get('/teachers', [TeachersController::class, 'getAllTeachers'])->name('teachers');
        Route::get('/teacher/create', [TeachersController::class, 'createTeacher'])->name('teacher.create');
        Route::post('/teacher/store', [TeachersController::class, 'storeTeacher'])->name('teacher.store');
        Route::get('/teacher/edit/{id}', [TeachersController::class, 'editTeacher'])->name('teacher.edit');
        Route::post('/teacher/update/{id}', [TeachersController::class, 'updateTeacher'])->name('teacher.update');
        Route::post('/teacher/delete/', [TeachersController::class, 'deleteTeacher'])->name('teacher.delete');

        Route::get('/assign-teachers', [TeachersController::class, 'getAllAssignedTeachers'])->name('assign-teachers');
        Route::get('/assign-teacher/create', [TeachersController::class, 'createAssign'])->name('assign-teacher.create');
        Route::post('/assign-teacher/store', [TeachersController::class, 'storeAssign'])->name('assign-teacher.store');
        Route::get('/assign-teacher/edit/{id}', [TeachersController::class, 'editAssign'])->name('assign-teacher.edit');
        Route::post('/assign-teacher/update/{id}', [TeachersController::class, 'updateAssign'])->name('assign-teacher.update');
        Route::post('/assign-teacher/delete/', [TeachersController::class, 'deleteAssign'])->name('assign-teacher.delete');
    });

});