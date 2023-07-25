<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function ($router) {
    Route::get('/countries', [ApiController::class, 'getCountries'])->name('countries');
    Route::get('/state/{country_id?}', [ApiController::class, 'getCountryStates'])->name('state');
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('log-in');
    Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [ApiAuthController::class, 'refresh'])->name('refresh');
    Route::get('/user-profile', [ApiAuthController::class, 'userProfile'])->name('user-profile'); 
    
    Route::get('/courses', [ApiAuthController::class, 'getAllCourses'])->name('courses');
    Route::get('/course-details', [ApiAuthController::class, 'getCourseDetails'])->name('course-details');
    Route::post('/update-userdata', [ApiAuthController::class, 'updateUserData'])->name('update-userdata');
    Route::post('/update-profile-image', [ApiAuthController::class, 'updateProfileImage'])->name('update-profile-image');
    Route::post('/change-password', [ApiAuthController::class, 'changePassword'])->name('change-password');
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
