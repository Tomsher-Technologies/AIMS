<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;

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
Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('log-in');
    Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [ApiAuthController::class, 'refresh'])->name('refresh');
    Route::get('/user-profile', [ApiAuthController::class, 'userProfile'])->name('profile'); 
    Route::get('/countries', [ApiAuthController::class, 'getCountries'])->name('countries');
    Route::get('/state/{country_id?}', [ApiAuthController::class, 'getCountryStates'])->name('state');
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
