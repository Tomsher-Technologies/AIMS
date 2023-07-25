<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomAuthController;

Route::namespace('Admin')->prefix('admin')->group(function () {

    Route::get('login', [CustomAuthController::class, 'index'])->name('login');
    Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');  
    Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

    Route::group(['middleware' => ['auth','admin']], function () {
        
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        
    });

});