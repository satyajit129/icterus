<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AssignLeadsController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\FacebookAdAccountsController;
use App\Http\Middleware\AdminProtectedRoute;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'adminLogin'])->name('adminLogin');

Route::prefix('admin')->group(function () {
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
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
            Route::get('/export', [AdminController::class, 'adminEmployeeExport'])->name('adminEmployeeExport');
        });

        Route::prefix('expense')->group(function () {
            Route::prefix('salary-expense')->group(function () {
                Route::get('/', [AdminController::class, 'adminSalaryExpense'])->name('adminSalaryExpense');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminSalaryExpenseCreateOrEdit'])->name('adminSalaryExpenseCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminSalaryExpenseSave'])->name('adminSalaryExpenseSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminSalaryExpenseDelete'])->name('adminSalaryExpenseDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminSalaryExpenseView'])->name('adminSalaryExpenseView');
                Route::post('/status-update', [AdminController::class, 'adminSalaryExpenseStatusUpdate'])->name('adminSalaryExpenseStatusUpdate');
                Route::get('/export', [AdminController::class, 'adminSalaryExpenseExport'])->name('adminSalaryExpenseExport');
            });
            Route::prefix('incentive-expense')->group(function () {
                Route::get('/', [AdminController::class, 'adminIncentiveExpense'])->name('adminIncentiveExpense');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminIncentiveExpenseCreateOrEdit'])->name('adminIncentiveExpenseCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminIncentiveExpenseSave'])->name('adminIncentiveExpenseSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminIncentiveExpenseDelete'])->name('adminIncentiveExpenseDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminIncentiveExpenseView'])->name('adminIncentiveExpenseView');
                Route::get('/export', [AdminController::class, 'adminIncentiveExpenseExport'])->name('adminIncentiveExpenseExport');
            });
            Route::prefix('office-expense')->group(function () {
                Route::get('/', [AdminController::class, 'adminOfficeExpense'])->name('adminOfficeExpense');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminOfficeExpenseCreateOrEdit'])->name('adminOfficeExpenseCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminOfficeExpenseSave'])->name('adminOfficeExpenseSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminOfficeExpenseDelete'])->name('adminOfficeExpenseDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminOfficeExpenseView'])->name('adminOfficeExpenseView');
                Route::get('/export', [AdminController::class, 'adminOfficeExpenseExport'])->name('adminOfficeExpenseExport');
            });
            Route::prefix('category')->group(function () {
                Route::get('/', [AdminController::class, 'adminExpenseCategory'])->name('adminExpenseCategory');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminExpenseCategoryCreateOrEdit'])->name('adminExpenseCategoryCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminExpenseCategorySave'])->name('adminExpenseCategorySave');
                Route::get('/delete/{id}', [AdminController::class, 'adminExpenseCategoryDelete'])->name('adminExpenseCategoryDelete');
            });
            Route::prefix('loan')->group(function () {
                Route::get('/', [AdminController::class, 'adminLoanList'])->name('adminLoanList');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminLoanCreateOrEdit'])->name('adminLoanCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminLoanSave'])->name('adminLoanSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminLoanDelete'])->name('adminLoanDelete');

                Route::post('/make-payment/{id?}', [AdminController::class, 'adminLoanMakePayment'])->name('adminLoanMakePayment');
                Route::get('/payment-details', [AdminController::class, 'adminLoanPaymentDetails'])->name('adminLoanPaymentDetails');
                Route::get('/payment-fatch', [AdminController::class, 'adminLoanPaymentFetch'])->name('adminLoanPaymentFetch');
                Route::post('/update/{id?}', [AdminController::class, 'adminLoanPaymentUpdate'])->name('adminLoanPaymentUpdate');
            });
        });
        Route::prefix('company')->group(function () {
            Route::get('/', [AdminController::class, 'adminCompanyList'])->name('adminCompanyList');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminCompanyCreateOrEdit'])->name('adminCompanyCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminCompanySave'])->name('adminCompanySave');
            Route::get('/delete/{id}', [AdminController::class, 'adminCompanyDelete'])->name('adminCompanyDelete');
            Route::get('/view/{id}', [AdminController::class, 'adminCompanyView'])->name('adminCompanyView');

            Route::prefix('deals')->group(function () {
                Route::get('/', [AdminController::class, 'adminCompanyDealsList'])->name('adminCompanyDealsList');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminCompanyDealsCreateOrEdit'])->name('adminCompanyDealsCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminCompanyDealsSave'])->name('adminCompanyDealsSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminCompanyDealsDelete'])->name('adminCompanyDealsDelete');
                Route::get('/view/{id}', [AdminController::class, 'adminCompanyDealsView'])->name('adminCompanyDealsView');
                Route::get('/export', [AdminController::class, 'adminCompanyDealsExport'])->name('adminCompanyDealsExport');

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
                Route::get('/export', [AdminController::class, 'adminEarningExport'])->name('adminEarningExport');
            });
        });
        Route::prefix('sales-status')->group(function () {
            Route::get('/', [AdminController::class, 'adminSalesStatus'])->name('adminSalesStatus');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminSalesStatusCreateOrEdit'])->name('adminSalesStatusCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminSalesStatusSave'])->name('adminSalesStatusSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminSalesStatusDelete'])->name('adminSalesStatusDelete');
        });
        Route::prefix('admin-user')->group(function () {
            Route::get('/', [AuthController::class, 'adminUserList'])->name('adminUserList');
            Route::get('/create-or-edit/{id?}', [AuthController::class, 'adminUserCreateOrEdit'])->name('adminUserCreateOrEdit');
            Route::post('/save/{id?}', [AuthController::class, 'adminUserSave'])->name('adminUserSave');
            Route::get('/delete/{id}', [AuthController::class, 'adminUserDelete'])->name('adminUserDelete');
        });
        Route::prefix('permission')->group(function () {
            Route::get('/', [AdminController::class, 'adminPermission'])->name('adminPermission');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminPermissionCreateOrEdit'])->name('adminPermissionCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminPermissionSave'])->name('adminPermissionSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminPermissionDelete'])->name('adminPermissionDelete');
        });
        Route::prefix('role-access')->group(function () {
            Route::get('/', [AdminController::class, 'adminRoleAccess'])->name('adminRoleAccess');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminRoleAccessCreateOrEdit'])->name('adminRoleAccessCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminRoleAccessSave'])->name('adminRoleAccessSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminRoleAccessDelete'])->name('adminRoleAccessDelete');
        });
        Route::prefix('student')->group(function () {
            Route::get('/list', [AdminController::class, 'adminStudentList'])->name('adminStudentList');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminStudentCreateOrEdit'])->name('adminStudentCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminStudentSave'])->name('adminStudentSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminStudentDelete'])->name('adminStudentDelete');

            Route::prefix('payment')->group(function () {
                Route::get('/list/{id}', [AdminController::class, 'adminStudentPaymentList'])->name('adminStudentPaymentList');
                Route::get('/create-or-edit/{student_id}/{payment_id?}', [AdminController::class, 'adminStudentPaymentCreateOrEdit'])->name('adminStudentPaymentCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminStudentPaymentSave'])->name('adminStudentPaymentSave');
                Route::get('/delete/{id}', [AdminController::class, 'adminStudentPaymentDelete'])->name('adminStudentPaymentDelete');
            });
        });
        Route::prefix('asset')->group(function () {
            Route::prefix('category')->group(function () {
                Route::get('/', [AdminController::class, 'adminAssetCategory'])->name('adminAssetCategory');
                Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminAssetCategoryCreateOrEdit'])->name('adminAssetCategoryCreateOrEdit');
                Route::post('/save/{id?}', [AdminController::class, 'adminAssetCategorySave'])->name('adminAssetCategorySave');
                Route::get('/delete/{id}', [AdminController::class, 'adminAssetCategoryDelete'])->name('adminAssetCategoryDelete');
            });
            Route::get('/', [AdminController::class, 'adminAssetList'])->name('adminAssetList');
            Route::get('/create-or-edit/{id?}', [AdminController::class, 'adminAssetCreateOrEdit'])->name('adminAssetCreateOrEdit');
            Route::post('/save/{id?}', [AdminController::class, 'adminAssetSave'])->name('adminAssetSave');
            Route::get('/delete/{id}', [AdminController::class, 'adminAssetDelete'])->name('adminAssetDelete');
        });

        Route::prefix('facebook-credentials')->group(function () {
            Route::get('/', [AdminController::class, 'adminFacebookCredentials'])->name('adminFacebookCredentials');
            Route::post('/update', [AdminController::class, 'adminFacebookCredentialsUpdate'])->name('adminFacebookCredentialsUpdate');
        });

        Route::prefix('facebook-pages')->group(function () {
            Route::get('/', [AdminController::class, 'adminFacebookPages'])->name('adminFacebookPages');
            Route::post('/sync', [AdminController::class, 'adminFacebookPagesSync'])->name('adminFacebookPagesSync');
            Route::get('/toggle-status/{id}', [AdminController::class, 'adminFacebookPageToggleStatus'])->name('adminFacebookPageToggleStatus');
            Route::get('/delete/{id}', [AdminController::class, 'adminFacebookPageDelete'])->name('adminFacebookPageDelete');
            Route::get('/view/{id}', [AdminController::class, 'adminFacebookPageView'])->name('adminFacebookPageView');
        });

        Route::prefix('facebook-leadgen-forms')->group(function () {
            Route::get('/', [AdminController::class, 'adminFacebookLeadgenForms'])->name('adminFacebookLeadgenForms');
            Route::post('/sync', [AdminController::class, 'adminFacebookLeadgenFormsSync'])->name('adminFacebookLeadgenFormsSync');
            Route::post('/sync-page/{pageId}', [AdminController::class, 'adminFacebookLeadgenFormsSyncPage'])->name('adminFacebookLeadgenFormsSyncPage');
            Route::get('/toggle-status/{id}', [AdminController::class, 'adminFacebookLeadgenFormToggleStatus'])->name('adminFacebookLeadgenFormToggleStatus');
            Route::get('/delete/{id}', [AdminController::class, 'adminFacebookLeadgenFormDelete'])->name('adminFacebookLeadgenFormDelete');
            Route::get('/view/{id}', [AdminController::class, 'adminFacebookLeadgenFormView'])->name('adminFacebookLeadgenFormView');
        });

        Route::prefix('facebook-leads')->group(function () {
            Route::get('/', [AdminController::class, 'adminFacebookLeads'])->name('adminFacebookLeads');
            Route::post('/collect', [AdminController::class, 'adminFacebookLeadsCollect'])->name('adminFacebookLeadsCollect');
            Route::post('/collect-optimized', [AdminController::class, 'adminFacebookLeadsCollectOptimized'])->name('adminFacebookLeadsCollectOptimized');
            Route::get('/toggle-status/{id}', [AdminController::class, 'adminFacebookLeadToggleStatus'])->name('adminFacebookLeadToggleStatus');
            Route::get('/delete/{id}', [AdminController::class, 'adminFacebookLeadDelete'])->name('adminFacebookLeadDelete');
            Route::get('/view/{id}', [AdminController::class, 'adminFacebookLeadView'])->name('adminFacebookLeadView');
            Route::get('/export', [AdminController::class, 'adminFacebookLeadsExport'])->name('adminFacebookLeadsExport');
            Route::get('/form-fields/{formId}', [AdminController::class, 'adminFacebookLeadsFormFields'])->name('adminFacebookLeadsFormFields');
            Route::get('/field-values/{fieldName}', [AdminController::class, 'adminFacebookLeadsFieldValues'])->name('adminFacebookLeadsFieldValues');
            Route::get('/form-stats/{formId}', [AdminController::class, 'adminFacebookLeadsFormStats'])->name('adminFacebookLeadsFormStats');
        });

        // Facebook Ad Accounts Routes
        Route::prefix('facebook-ad-accounts')->group(function () {
            Route::get('/', [FacebookAdAccountsController::class, 'index'])->name('adminFacebookAdAccounts');
            Route::post('/sync', [FacebookAdAccountsController::class, 'sync'])->name('adminFacebookAdAccountsSync');
            Route::get('/toggle/{id}', [FacebookAdAccountsController::class, 'toggle'])->name('adminFacebookAdAccountToggle');
            Route::get('/delete/{id}', [FacebookAdAccountsController::class, 'destroy'])->name('adminFacebookAdAccountDelete');
        });

        // Assign Leads Routes
        Route::prefix('assign-leads')->group(function () {
            Route::get('/', [AssignLeadsController::class, 'index'])->name('adminAssignLeads');
            Route::post('/bulk-assign', [AssignLeadsController::class, 'bulkAssign'])->name('adminAssignLeadsBulk');
            Route::post('/individual-assign', [AssignLeadsController::class, 'individualAssign'])->name('adminAssignLeadsIndividual');
            Route::post('/unassign', [AssignLeadsController::class, 'unassign'])->name('adminAssignLeadsUnassign');
        });

        Route::prefix('lead')->group(function(){
            Route::get('/',[AssignLeadsController::class,'leadList'])->name('leadList');
            Route::get('/view/{id?}',[AssignLeadsController::class,'leadView'])->name('leadView');
            Route::get('/edit/{id?}',[AssignLeadsController::class,'leadEdit'])->name('leadEdit');
            Route::post('/save/{id?}',[AssignLeadsController::class,'leadSave'])->name('leadSave');
        });
    });
});
