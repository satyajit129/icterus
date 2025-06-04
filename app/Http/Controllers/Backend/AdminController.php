<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use App\Services\EmployeeService;
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
    protected $salaryExpenseService;

    public function __construct(SettingService $settingService, DesignationService $designationService, DepartmentService $departmentService, EmployeeService $employeeService, SalaryExpenseService $salaryExpenseService)
    {
        $this->settingService = $settingService;
        $this->designationService = $designationService;
        $this->departmentService = $departmentService;
        $this->employeeService = $employeeService;
        $this->salaryExpenseService = $salaryExpenseService;
    }

    public function adminDashboard(): \Illuminate\View\View
    {
        return view('backend.pages.admin_dashboard');
    }
    public function adminSettings(): \Illuminate\View\View
    {
        return $this->settingService->renderSettingsPage();
    }
    public function adminSettingsUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'website_name' => 'required|string',
                'website_email' => 'required|email',
                'copy_right_text' => 'required|string',
                'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'favicon' => 'nullable|image|mimes:ico,jpg,jpeg,png|max:1024',
            ]);
            return $this->settingService->handleSettingsUpdate($request);
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
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

}
