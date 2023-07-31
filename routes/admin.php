<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomAuthController;
use App\Http\Controllers\Admin\HomeController;

Route::namespace('Admin')->prefix('admin')->group(function () {

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
    });

});