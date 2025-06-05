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

        Route::prefix('designation')->group(function () {
            Route::get('/', [AdminController::class, 'adminDesignation'])->name('adminDesignation');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminDesignationCreateOrEdit'])->name('adminDesignationCreateOrEdit');
            Route::post('/save/{id?}',[AdminController::class,'adminDesignationSave'])->name('adminDesignationSave');
            Route::get('/delete/{id}',[AdminController::class,'adminDesignationDelete'])->name('adminDesignationDelete');
        });
        Route::prefix('department')->group(function(){
            Route::get('/', [AdminController::class, 'adminDepartment'])->name('adminDepartment');
            Route::get('/create-or-edit/{id?}',[AdminController::class,'adminDepartmentCreateOrEdit'])->name('adminDepartmentCreateOrEdit');
            Route::post('/save/{id?}',[AdminController::class,'adminDepartmentSave'])->name('adminDepartmentSave');
            Route::get('/delete/{id}',[AdminController::class,'adminDepartmentDelete'])->name('adminDepartmentDelete');
        });

        Route::prefix('employee')->group(function(){
            Route::get('/',[AdminController::class,'adminEmployeeList'])->name('adminEmployeeList');
            Route::get('/create-or-edit/{id?}',[AdminController::class,'adminEmployeeCreateOrEdit'])->name('adminEmployeeCreateOrEdit');
            Route::post('/save/{id?}',[AdminController::class,'adminEmployeeSave'])->name('adminEmployeeSave');
            Route::get('/delete/{id}',[AdminController::class,'adminEmployeeDelete'])->name('adminEmployeeDelete');
            Route::get('/data',[AdminController::class,'adminEmployeeData'])->name('adminEmployeeData');
        });

        Route::prefix('expense')->group(function(){
            Route::prefix('salary-expense')->group(function(){
                Route::get('/',[AdminController::class,'adminSalaryExpense'])->name('adminSalaryExpense');
                Route::get('/create-or-edit/{id?}',[AdminController::class,'adminSalaryExpenseCreateOrEdit'])->name('adminSalaryExpenseCreateOrEdit');
                Route::post('/save/{id?}',[AdminController::class,'adminSalaryExpenseSave'])->name('adminSalaryExpenseSave');
                Route::get('/delete/{id}',[AdminController::class,'adminSalaryExpenseDelete'])->name('adminSalaryExpenseDelete');
            });
            Route::prefix('incentive-expense')->group(function(){
                Route::get('/',[AdminController::class,'adminIncentiveExpense'])->name('adminIncentiveExpense');
                Route::get('/create-or-edit/{id?}',[AdminController::class,'adminIncentiveExpenseCreateOrEdit'])->name('adminIncentiveExpenseCreateOrEdit');
                Route::post('/save/{id?}',[AdminController::class,'adminIncentiveExpenseSave'])->name('adminIncentiveExpenseSave');
                Route::get('/delete/{id}',[AdminController::class,'adminIncentiveExpenseDelete'])->name('adminIncentiveExpenseDelete');
            });
            Route::prefix('office-expense')->group(function(){
                Route::get('/',[AdminController::class,'adminOfficeExpense'])->name('adminOfficeExpense');
                Route::get('/create-or-edit/{id?}',[AdminController::class,'adminOfficeExpenseCreateOrEdit'])->name('adminOfficeExpenseCreateOrEdit');
                Route::post('/save/{id?}',[AdminController::class,'adminOfficeExpenseSave'])->name('adminOfficeExpenseSave');
                Route::get('/delete/{id}',[AdminController::class,'adminOfficeExpenseDelete'])->name('adminOfficeExpenseDelete');
            });
        });
    });

});
