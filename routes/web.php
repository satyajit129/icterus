<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Middleware\AdminProtectedRoute;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'adminLogin'])->name('adminLogin');
    Route::post('/login-request', [AuthController::class, 'adminLoginRequest'])->name('adminLoginRequest');

    Route::middleware([AdminProtectedRoute::class])->group(function () {
        Route::get('/dashboard',[AdminController::class, 'adminDashboard'])->name('adminDashboard');
        Route::get('/logout',[AuthController::class,'adminLogout'])->name('adminLogout');
        Route::get('/settings',[AdminController::class,'adminSettings'])->name('adminSettings');
        Route::post('/settings-update',[AdminController::class,'adminSettingsUpdate'])->name('adminSettingsUpdate');
        Route::get('/logout', [AuthController::class, 'adminLogout'])->name('adminLogout');
        Route::get('designation', [AdminController::class, 'adminDesignation'])->name('adminDesignation');
    });

});

