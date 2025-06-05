<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\SalaryExpense;
use App\Services\DashboardService;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use App\Services\EmployeeService;
use App\Services\IncentiveExpenseService;
use App\Services\OfficeExpenseService;
use App\Services\SalaryExpenseService;
use App\Services\SettingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    public function __construct(SettingService $settingService, DesignationService $designationService, DepartmentService $departmentService, EmployeeService $employeeService, SalaryExpenseService $salaryExpenseService, IncentiveExpenseService $incentiveExpenseService, OfficeExpenseService $officeExpenseService,
    DashboardService $dashboardService)
    {
        $this->settingService = $settingService;
        $this->designationService = $designationService;
        $this->departmentService = $departmentService;
        $this->employeeService = $employeeService;
        $this->salaryExpenseService = $salaryExpenseService;
        $this->incentiveExpenseService = $incentiveExpenseService;
        $this->officeExpenseService = $officeExpenseService;
        $this->dashboardService = $dashboardService;
    }

    public function adminDashboard(): \Illuminate\View\View
    {
        return $this->dashboardService->renderDashboard();
    }
    public function adminSettings(): \Illuminate\View\View
    {
        return $this->settingService->renderSettingsPage();
    }
    public function adminSettingsUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        return $this->settingService->handleSettingsUpdate($request);
    }
    public function adminDesignation(): \Illuminate\View\View
    {
        return $this->designationService->renderDesignationPage();
    }
    public function adminDesignationCreateOrEdit($id = null): \Illuminate\View\View
    {
        return $this->designationService->renderDesignationCreateOrEditPage($id);
    }
    public function adminDesignationSave(Request $request, $id = null): \Illuminate\Http\RedirectResponse
    {
        return $this->designationService->handleDesignationSave($request, $id);
    }
    public function adminDesignationDelete($id): \Illuminate\Http\RedirectResponse
    {
        return $this->designationService->handleDesignationDelete($id);
    }
    public function adminDepartment(): \Illuminate\View\View
    {
        return $this->departmentService->renderDepartmentPage();
    }
    public function adminDepartmentCreateOrEdit($id = null): \Illuminate\View\View
    {
        return $this->departmentService->renderDepartmentCreateOrEditPage($id);
    }
    public function adminDepartmentSave(Request $request, $id = null): \Illuminate\Http\RedirectResponse
    {
        return $this->departmentService->handleDepartmentSave($request, $id);
    }
    public function adminDepartmentDelete($id): \Illuminate\Http\RedirectResponse
    {
        return $this->departmentService->handleDepartmentDelete($id);
    }
    public function adminEmployeeList():\Illuminate\View\View
    {
        return $this->employeeService->renderEmployeeList();
    }
    public function adminEmployeeCreateOrEdit($id = null):\Illuminate\View\View
    {
        return $this->employeeService->renderEmployeeCreateOrEditPage($id);
    }
    public function adminEmployeeSave(Request $request, $id=null): \Illuminate\Http\RedirectResponse
    {
        return $this->employeeService->handleEmployeeSave($request, $id);
    }
    public function adminEmployeeDelete($id): \Illuminate\Http\RedirectResponse
    {
        return $this->employeeService->handleEmployeeDelete($id);
    }
    public function adminEmployeeData(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->employeeService->getEmployeeData($request);
    }
    public function adminSalaryExpense(): \Illuminate\View\View
    {
        return $this->salaryExpenseService->renderSalaryExpenseList();
    }
    public function adminSalaryExpenseCreateOrEdit($id = null): \Illuminate\View\View
    {
        return $this->salaryExpenseService->renderSalaryExpenseCreateOrEditPage($id);
    }
    public function adminSalaryExpenseSave(Request $request, $id=null): \Illuminate\Http\RedirectResponse
    {
        return $this->salaryExpenseService->handleSalaryExpenseSave($request, $id);
    }
    public function adminSalaryExpenseDelete($id):\Illuminate\Http\RedirectResponse
    {
        return $this->salaryExpenseService->handleSalaryExpenseDelete($id);
    }
    public function adminIncentiveExpense(): \Illuminate\View\View
    {
        return $this->incentiveExpenseService->renderIncentiveExpense();
    }
    public function adminIncentiveExpenseCreateOrEdit($id = null):\Illuminate\View\View
    {
        return $this->incentiveExpenseService->renderIncentiveExpenseCreateOrEditPage($id);
    }
    public function adminIncentiveExpenseSave(Request $request , $id=null): \Illuminate\Http\RedirectResponse
    {
        return $this->incentiveExpenseService->handleIncentiveExpenseSave( $request,$id);
    }
    public function adminIncentiveExpenseDelete($id):\Illuminate\Http\RedirectResponse
    {
        return $this->incentiveExpenseService->handleIncentiveExpenseDelete($id);
    }
    public function adminOfficeExpense(): \Illuminate\View\View
    {
        return $this->officeExpenseService->renderOfficeExpense();
    }
    public function adminOfficeExpenseCreateOrEdit($id = null):\Illuminate\View\View
    {
        return $this->officeExpenseService->renderOfficeExpenseCreateOrEditPage($id);
    }
    public function adminOfficeExpenseSave(Request $request , $id=null): \Illuminate\Http\RedirectResponse
    {
        return $this->officeExpenseService->handleOfficeExpenseSave( $request,$id);
    }
    public function adminOfficeExpenseDelete($id):\Illuminate\Http\RedirectResponse
    {
        return $this->officeExpenseService->handleOfficeExpenseDelete($id);
    }
}
