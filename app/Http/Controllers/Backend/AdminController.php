<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use App\Services\DashboardService;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use App\Services\EmployeeService;
use App\Services\IncentiveExpenseService;
use App\Services\LoanService;
use App\Services\OfficeExpenseService;
use App\Services\RolePermisionService;
use App\Services\SalaryExpenseService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    protected SettingService $settingService;
    protected DesignationService $designationService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;
    protected SalaryExpenseService $salaryExpenseService;
    protected IncentiveExpenseService $incentiveExpenseService;
    protected OfficeExpenseService $officeExpenseService;
    protected DashboardService $dashboardService;
    protected CompanyService $companyService;
    protected RolePermisionService $rolePermisionService;
    protected LoanService $loanService;

    public function __construct(
        SettingService $settingService,
        DesignationService $designationService,
        DepartmentService $departmentService,
        EmployeeService $employeeService,
        SalaryExpenseService $salaryExpenseService,
        IncentiveExpenseService $incentiveExpenseService,
        OfficeExpenseService $officeExpenseService,
        DashboardService $dashboardService,
        CompanyService $companyService,
        RolePermisionService $rolePermisionService,
        LoanService $loanService,
    ) {
        $this->settingService = $settingService;
        $this->designationService = $designationService;
        $this->departmentService = $departmentService;
        $this->employeeService = $employeeService;
        $this->salaryExpenseService = $salaryExpenseService;
        $this->incentiveExpenseService = $incentiveExpenseService;
        $this->officeExpenseService = $officeExpenseService;
        $this->dashboardService = $dashboardService;
        $this->companyService = $companyService;
        $this->rolePermisionService = $rolePermisionService;
        $this->loanService = $loanService;
    }

    public function adminDashboard(Request $request): View
    {
        return $this->dashboardService->renderDashboard($request);
    }
    public function adminSettings(): View
    {
        return $this->settingService->renderSettingsPage();
    }
    public function adminSettingsUpdate(Request $request): RedirectResponse
    {
        return $this->settingService->handleSettingsUpdate($request);
    }
    public function adminDesignation(): View
    {
        return $this->designationService->renderDesignationPage();
    }
    public function adminDesignationCreateOrEdit($id = null): View
    {
        return $this->designationService->renderDesignationCreateOrEditPage($id);
    }
    public function adminDesignationSave(Request $request, $id = null): RedirectResponse
    {
        return $this->designationService->handleDesignationSave($request, $id);
    }
    public function adminDesignationDelete($id): RedirectResponse
    {
        return $this->designationService->handleDesignationDelete($id);
    }
    public function adminDepartment(): View
    {
        return $this->departmentService->renderDepartmentPage();
    }
    public function adminDepartmentCreateOrEdit($id = null): View
    {
        return $this->departmentService->renderDepartmentCreateOrEditPage($id);
    }
    public function adminDepartmentSave(Request $request, $id = null): RedirectResponse
    {
        return $this->departmentService->handleDepartmentSave($request, $id);
    }
    public function adminDepartmentDelete($id): RedirectResponse
    {
        return $this->departmentService->handleDepartmentDelete($id);
    }
    public function adminEmployeeList(): View
    {
        return $this->employeeService->renderEmployeeList();
    }
    public function adminEmployeeCreateOrEdit($id = null): View
    {
        return $this->employeeService->renderEmployeeCreateOrEditPage($id);
    }
    public function adminEmployeeSave(Request $request, $id = null): RedirectResponse
    {
        return $this->employeeService->handleEmployeeSave($request, $id);
    }
    public function adminEmployeeDelete($id): RedirectResponse
    {
        return $this->employeeService->handleEmployeeDelete($id);
    }
    public function adminEmployeeData(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->employeeService->getEmployeeData($request);
    }
    public function adminEmployeeView($id): View
    {
        return $this->employeeService->seeEmployeeData($id);
    }
    public function adminSalaryExpense(Request $request): View
    {
        return $this->salaryExpenseService->renderSalaryExpenseList($request);
    }
    public function adminSalaryExpenseCreateOrEdit($id = null): View
    {
        return $this->salaryExpenseService->renderSalaryExpenseCreateOrEditPage($id);
    }
    public function adminSalaryExpenseSave(Request $request, $id = null): RedirectResponse
    {
        return $this->salaryExpenseService->handleSalaryExpenseSave($request, $id);
    }
    public function adminSalaryExpenseDelete($id): RedirectResponse
    {
        return $this->salaryExpenseService->handleSalaryExpenseDelete($id);
    }
    public function adminSalaryExpenseView($id): View
    {
        return $this->salaryExpenseService->renderSalaryExpenseView($id);
    }
    public function adminSalaryExpenseStatusUpdate(Request $request): RedirectResponse
    {
        return $this->salaryExpenseService->handleSalaryExpenseStatusUpdate($request);
    }
    public function adminIncentiveExpense(Request $request): View
    {
        return $this->incentiveExpenseService->renderIncentiveExpense($request);
    }
    public function adminIncentiveExpenseCreateOrEdit($id = null): View
    {
        return $this->incentiveExpenseService->renderIncentiveExpenseCreateOrEditPage($id);
    }
    public function adminIncentiveExpenseSave(Request $request, $id = null): RedirectResponse
    {
        return $this->incentiveExpenseService->handleIncentiveExpenseSave($request, $id);
    }
    public function adminIncentiveExpenseDelete($id): RedirectResponse
    {
        return $this->incentiveExpenseService->handleIncentiveExpenseDelete($id);
    }
    public function adminIncentiveExpenseView($id): View
    {
        return $this->incentiveExpenseService->renderIncentiveExpenseView($id);
    }
    public function adminOfficeExpense(Request $request): View
    {
        return $this->officeExpenseService->renderOfficeExpense($request);
    }
    public function adminOfficeExpenseCreateOrEdit($id = null): View
    {
        return $this->officeExpenseService->renderOfficeExpenseCreateOrEditPage($id);
    }
    public function adminOfficeExpenseSave(Request $request, $id = null): RedirectResponse
    {
        return $this->officeExpenseService->handleOfficeExpenseSave($request, $id);
    }
    public function adminOfficeExpenseDelete($id): RedirectResponse
    {
        return $this->officeExpenseService->handleOfficeExpenseDelete($id);
    }
    public function adminOfficeExpenseView($id): View
    {
        return $this->officeExpenseService->renderOfficeExpenseView($id);
    }
    public function adminCompanyList(): View
    {
        return $this->companyService->renderCompanyList();
    }
    public function adminCompanyCreateOrEdit($id = null): View
    {
        return $this->companyService->renderCompanyCreateOrEditPage($id);
    }
    public function adminCompanySave(Request $request, $id = null): RedirectResponse
    {
        return $this->companyService->handleCompanySave($request, $id);
    }
    public function adminCompanyDelete($id): RedirectResponse
    {
        return $this->companyService->handleCompanyDelete($id);
    }
    public function adminCompanyView($id): View
    {
        return $this->companyService->renderCompanyView($id);
    }
    public function adminCompanyDealsList(): View
    {
        return $this->companyService->renderCompanyDealsList();
    }
    public function adminCompanyDealsCreateOrEdit($id = null): View
    {
        return $this->companyService->renderCompanyDealsCreateOrEdit($id);
    }
    public function adminCompanyDealsSave(Request $request, $id = null): RedirectResponse
    {
        return $this->companyService->handleCompanyDealsSave($request, $id);
    }
    public function adminCompanyDealsDelete($id): RedirectResponse
    {
        return $this->companyService->handleCompanyDealsDelete($id);
    }
    public function adminCompanyDealsView($id): View
    {
        return $this->companyService->renderCompanyDealsView($id);
    }
    public function adminDealsPayment($id): View
    {
        return $this->companyService->renderadminDealsPayment($id);
    }
    public function adminDealsPaymentCreateOrEdit(Request $request, $id= null): View
    {
        return $this->companyService->renderDealsPaymentCreateOrEdit($request, $id);
    }
    public function adminDealsPaymentSave(Request $request, $id = null): RedirectResponse
    {
        return $this->companyService->handleDealsPaymentSave($request, $id);
    }
    public function adminDealsPaymentDelete($id): RedirectResponse
    {
        return $this->companyService->handleDealsPaymentDelete($id);
    }
    public function adminDealsPaymentView($id): View
    {
        return $this->companyService->renderDealsPaymentView($id);
    }
    public function adminEarningList(Request $request): View
    {
        return $this->companyService->renderEarningList($request);
    }
    public function adminEarningCreateOrEdit($id = null): View
    {
        return $this->companyService->renderEarningCreateOrEdit($id);
    }
    public function adminEarningSave(Request $request, $id = null): RedirectResponse
    {
        return $this->companyService->handleEarningSave($request, $id);
    }
    public function adminEarningDelete($id): RedirectResponse
    {
        return $this->companyService->handleEarningDelete($id);
    }
    public function adminEarningView($id): View
    {
        return $this->companyService->renderEarningView($id);
    }
    public function adminPermission(): View
    {
        return $this->rolePermisionService->renderAdminPermissionList();
    }
    public function adminPermissionCreateOrEdit($id = null): View
    {
        return $this->rolePermisionService->renderPermissionCreateOrEdit($id);
    }
    public function adminPermissionSave(Request $request, $id=null): RedirectResponse
    {
        return $this->rolePermisionService->handlePermissionSave($request, $id);
    }
    public function adminPermissionDelete($id): RedirectResponse
    {
        return $this->rolePermisionService->handlePermissionDelete($id);
    }
    public function adminRoleAccess(): View
    {
        return $this->rolePermisionService->renderRoleAccess();
    }
    public function adminRoleAccessCreateOrEdit($id = null): View
    {
        return $this->rolePermisionService->renderRoleAccessCreateOrEdit($id);
    }
    public function adminRoleAccessSave(Request $request, $id= null): RedirectResponse
    {
        return $this->rolePermisionService->handleRoleAccessSave($request, $id);
    }
    public function adminRoleAccessDelete($id): RedirectResponse
    {
        return $this->rolePermisionService->handleRoleAccessDelete($id);
    }
    public function adminSalesStatus(): View
    {
        return $this->officeExpenseService->renderSalesStatus();
    }
    public function adminSalesStatusCreateOrEdit($id = null): View
    {
        return $this->officeExpenseService->renderSalesStatusCreateOrEdit($id);
    }
    public function adminSalesStatusSave(Request $request, $id= null): RedirectResponse
    {
        return $this->officeExpenseService->handleSalesStatusSave($request, $id);
    }
    public function adminSalesStatusDelete($id): RedirectResponse
    {
        return $this->officeExpenseService->handleSalesStatusDelete($id);
    }
    public function adminExpenseCategory(): View
    {
        return $this->officeExpenseService->renderExpenseCategory();
    }
    public function adminExpenseCategoryCreateOrEdit($id = null): View
    {
        return $this->officeExpenseService->renderExpenseCategoryCreateOrEdit($id);
    }
    public function adminExpenseCategorySave(Request $request, $id = null): RedirectResponse
    {
        return $this->officeExpenseService->handleExpenseCategorySave($request, $id);
    }
    public function adminExpenseCategoryDelete($id): RedirectResponse
    {
        return $this->officeExpenseService->handleExpenseCategoryDelete($id);
    }
    public function adminLoanList(): View
    {
        return $this->loanService->renderLoanList();
    }
    public function adminLoanCreateOrEdit($id = null): View
    {
        return $this->loanService->renderLoanCreateOrEdit($id);
    }
    public function adminLoanSave(Request $request, $id = null): RedirectResponse
    {
        return $this->loanService->handleLoanSave($request, $id);
    }
    public function adminLoanDelete($id): RedirectResponse
    {
        return $this->loanService->handleLoanDelete($id);
    }
    public function adminLoanMakePayment(Request $request, $id= null): RedirectResponse
    {
        return $this->loanService->handleLoanMakePayment($request, $id);
    }
    public function adminLoanPaymentDetails(Request $request): View
    {
        return $this->loanService->renderLoanPaymentDetails($request);
    }
    public function adminLoanPaymentFetch(Request $request): View
    {
        return $this->loanService->renderLoanPaymentFetch($request);
    }
    public function adminLoanPaymentUpdate(Request $request): RedirectResponse
    {
        return $this->loanService->handleLoanPaymentUpdate($request);
    }

}
