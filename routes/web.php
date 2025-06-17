<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Middleware\AdminProtectedRoute;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'adminLogin'])->name('adminLogin');
    Route::post('/login-request', [AuthController::class, 'adminLoginRequest'])->name('adminLoginRequest');

    Route::middleware([AdminProtectedRoute::class])->group(function () {
        Route::get('/profile', [AuthController::class, 'adminProfile'])->name('adminProfile');
        Route::post('/profile-update', [AuthController::class, 'adminProfileUpdate'])->name('adminProfileUpdate');
        Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('adminDashboard');
        Route::get('/logout', [AuthController::class, 'adminLogout'])->name('adminLogout');
        Route::get('/settings', [AdminController::class, 'adminSettings'])->name('adminSettings');
        Route::post('/settings-update', [AdminController::class, 'adminSettingsUpdate'])->name('adminSettingsUpdate');
        Route::get('/logout', [AuthController::class, 'adminLogout'])->name('adminLogout');

        Route::prefix('designation')->group(function () {
            Route::get('/', [AdminController::class, 'adminDesignation'])->name('adminDesignation');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminDesignationCreateOrEdit'])->name('adminDesignationCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminDesignationSave'])->name('adminDesignationSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminDesignationDelete'])->name('adminDesignationDelete');
        });
        Route::prefix('department')->group(function () {
            Route::get('/', [AdminController::class, 'adminDepartment'])->name('adminDepartment');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminDepartmentCreateOrEdit'])->name('adminDepartmentCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminDepartmentSave'])->name('adminDepartmentSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminDepartmentDelete'])->name('adminDepartmentDelete');
        });

        Route::prefix('employee')->group(function () {
            Route::get('/', [AdminController::class, 'adminEmployeeList'])->name('adminEmployeeList');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminEmployeeCreateOrEdit'])->name('adminEmployeeCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminEmployeeSave'])->name('adminEmployeeSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminEmployeeDelete'])->name('adminEmployeeDelete');
            Route::get('/data', [AdminController::class, 'adminEmployeeData'])->name('adminEmployeeData');
            Route::get('/view/{id}', [AdminController::class, 'adminEmployeeView'])->name('adminEmployeeView');
        });

        Route::prefix('expense')->group(function () {
            Route::prefix('salary-expense')->group(function () {
                Route::get('/', [AdminController::class, 'adminSalaryExpense'])->name('adminSalaryExpense');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminSalaryExpenseCreateOrEdit'])->name('adminSalaryExpenseCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminSalaryExpenseSave'])->name('adminSalaryExpenseSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminSalaryExpenseDelete'])->name('adminSalaryExpenseDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminSalaryExpenseView'])->name('adminSalaryExpenseView');
            });
            Route::prefix('incentive-expense')->group(function () {
                Route::get('/', [AdminController::class, 'adminIncentiveExpense'])->name('adminIncentiveExpense');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminIncentiveExpenseCreateOrEdit'])->name('adminIncentiveExpenseCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminIncentiveExpenseSave'])->name('adminIncentiveExpenseSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminIncentiveExpenseDelete'])->name('adminIncentiveExpenseDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminIncentiveExpenseView'])->name('adminIncentiveExpenseView');
            });
            Route::prefix('office-expense')->group(function () {
                Route::get('/', [AdminController::class, 'adminOfficeExpense'])->name('adminOfficeExpense');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminOfficeExpenseCreateOrEdit'])->name('adminOfficeExpenseCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminOfficeExpenseSave'])->name('adminOfficeExpenseSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminOfficeExpenseDelete'])->name('adminOfficeExpenseDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminOfficeExpenseView'])->name('adminOfficeExpenseView');
            });
        });
        Route::prefix('company')->group(function () {
            Route::get('/', [AdminController::class, 'adminCompanyList'])->name('adminCompanyList');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminCompanyCreateOrEdit'])->name('adminCompanyCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminCompanySave'])->name('adminCompanySave');
            Route::get('/delete/{id}', [AdminController::class, 'adminCompanyDelete'])->name('adminCompanyDelete');
            Route::get('/view/{id}', [AdminController::class, 'adminCompanyView'])->name('adminCompanyView');

            Route::prefix('/deals')->group(function () {
                Route::get('/', [AdminController::class, 'adminCompanyDealsList'])->name('adminCompanyDealsList');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminCompanyDealsCreateOrEdit'])->name('adminCompanyDealsCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminCompanyDealsSave'])->name('adminCompanyDealsSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminCompanyDealsDelete'])->name('adminCompanyDealsDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminCompanyDealsView'])->name('adminCompanyDealsView');

                Route::prefix('payment')->group(function () {
                    Route::get('/list/{id}', [AdminController::class, 'adminDealsPayment'])->name('adminDealsPayment');
                    Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminDealsPaymentCreateOrEdit'])->name('adminDealsPaymentCreateOrEdit');
                    Route::post('/save/{id?}', [AdminController::class, 'adminDealsPaymentSave'])->name('adminDealsPaymentSave');
                    Route::get('/delete/{id}', [AdminController::class, 'adminDealsPaymentDelete'])->name('adminDealsPaymentDelete');
                    Route::get('/view/{id}', [AdminController::class, 'adminDealsPaymentView'])->name('adminDealsPaymentView');
                });
            });
            Route::prefix('earning')->group(function () {
                Route::get('/', [AdminController::class, 'adminEarningList'])->name('adminEarningList');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminEarningCreateOrEdit'])->name('adminEarningCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminEarningSave'])->name('adminEarningSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminEarningDelete'])->name('adminEarningDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminEarningView'])->name('adminEarningView');
            });
        });
        Route::prefix('/admin-user')->group(function () {
            Route::get('/', [AuthController::class, 'adminUserList'])->name('adminUserList');
            Route::get('/create-or-edit/{id?}', [AuthController::class, 'adminUserCreateOrEdit'])->name('adminUserCreateOrEdit');
            Route::post('/save/{id?}', [AuthController::class, 'adminUserSave'])->name('adminUserSave');
            Route::get('/delete/{id}', [AuthController::class, 'adminUserDelete'])->name('adminUserDelete');
        });

        Route::prefix('role')->group(function () {
            Route::get('/', [AdminController::class, 'adminRole'])->name('adminRole');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminRoleCreateOrEdit'])->name('adminRoleCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminRoleSave'])->name('adminRoleSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminRoleDelete'])->name('adminRoleDelete');
        });
        Route::prefix('permission')->group(function () {
            Route::get('/', [AdminController::class, 'adminPermission'])->name('adminPermission');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminPermissionCreateOrEdit'])->name('adminPermissionCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminPermissionSave'])->name('adminPermissionSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminPermissionDelete'])->name('adminPermissionDelete');
        });
    });
});
