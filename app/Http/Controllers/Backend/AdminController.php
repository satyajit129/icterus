<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AssetService;
use App\Services\CompanyService;
use App\Services\DashboardService;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use App\Services\EmployeeService;
use App\Services\FacebookCredentialsService;
use App\Services\FacebookLeadgenFormService;
use App\Services\FacebookLeadService;
use App\Services\FacebookPageService;
use App\Services\IncentiveExpenseService;
use App\Services\LoanService;
use App\Services\OfficeExpenseService;
use App\Services\RolePermisionService;
use App\Services\SalaryExpenseService;
use App\Services\SettingService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    protected StudentService $studentService;
    protected AssetService $assetService;
    protected FacebookCredentialsService $facebookCredentialsService;
    protected FacebookPageService $facebookPageService;
    protected FacebookLeadgenFormService $facebookLeadgenFormService;
    protected FacebookLeadService $facebookLeadService;

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
        StudentService $studentService,
        AssetService $assetService,
        FacebookCredentialsService $facebookCredentialsService,
        FacebookPageService $facebookPageService,
        FacebookLeadgenFormService $facebookLeadgenFormService,
        FacebookLeadService $facebookLeadService,
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
        $this->studentService = $studentService;
        $this->assetService = $assetService;
        $this->facebookCredentialsService = $facebookCredentialsService;
        $this->facebookPageService = $facebookPageService;
        $this->facebookLeadgenFormService = $facebookLeadgenFormService;
        $this->facebookLeadService = $facebookLeadService;
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

    public function adminSettingsTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255'
        ]);

        try {
            $emailService = new \App\Services\EmailService();
            $settings = \App\Models\Setting::first();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Settings not found. Please configure settings first.'
                ], 400);
            }

            $emailSent = $emailService->testEmailConfiguration($request->test_email);

            if ($emailSent) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test email sent successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test email. Please check your email configuration.'
                ], 500);
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Test email error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
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
    public function adminEmployeeExport(): BinaryFileResponse
    {
        return $this->employeeService->renderEmployeeExport();
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
    public function adminSalaryExpenseExport(Request $request): BinaryFileResponse
    {
        return $this->salaryExpenseService->renderSalaryExpenseExport($request);
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
    public function adminIncentiveExpenseExport(Request $request): BinaryFileResponse
    {
        return $this->incentiveExpenseService->renderIncentiveExpenseExport($request);
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
    public function adminOfficeExpenseExport(Request $request): BinaryFileResponse
    {
        return $this->officeExpenseService->renderOfficeExpenseExport($request);
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
    public function adminCompanyDealsExport(): BinaryFileResponse
    {
        return $this->companyService->renderCompanyDealsExport();
    }
    public function adminDealsPayment($id): View
    {
        return $this->companyService->renderadminDealsPayment($id);
    }
    public function adminDealsPaymentCreateOrEdit(Request $request, $id = null): View
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
    public function adminEarningExport(Request $request): BinaryFileResponse
    {
        return $this->companyService->renderEarningExport($request);
    }
    public function adminPermission(): View
    {
        return $this->rolePermisionService->renderAdminPermissionList();
    }
    public function adminPermissionCreateOrEdit($id = null): View
    {
        return $this->rolePermisionService->renderPermissionCreateOrEdit($id);
    }
    public function adminPermissionSave(Request $request, $id = null): RedirectResponse
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
    public function adminRoleAccessSave(Request $request, $id = null): RedirectResponse
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
    public function adminSalesStatusSave(Request $request, $id = null): RedirectResponse
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
    public function adminLoanMakePayment(Request $request, $id = null): RedirectResponse
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
    public function AdminStudentList(): View
    {
        return $this->studentService->renderStudentList();
    }
    public function adminStudentCreateOrEdit($id = null): View
    {
        return $this->studentService->renderStudentCreateOrEdit($id);
    }
    public function adminStudentSave(Request $request, $id = null): RedirectResponse
    {
        return $this->studentService->handleStudentSave($request, $id);
    }
    public function adminStudentDelete($id): RedirectResponse
    {
        return $this->studentService->handleStudentDelete($id);
    }
    public function adminStudentPaymentList($student_id): View
    {
        return $this->studentService->renderStudentPaymentList($student_id);
    }
    public function adminStudentPaymentCreateOrEdit($student_id, $payment_id = null): View
    {
        return $this->studentService->renderStudentPaymentCreateOrEdit($student_id, $payment_id);
    }
    public function adminStudentPaymentSave(Request $request, $id = null): RedirectResponse
    {
        return $this->studentService->handleStudentPaymentSave($request, $id);
    }
    public function adminAssetCategory(): View
    {
        return $this->assetService->renderAssetCategory();
    }
    public function adminAssetCategoryCreateOrEdit($id = null): View
    {
        return $this->assetService->renderAssetCategoryCreateOrEdit($id);
    }
    public function adminAssetCategorySave(Request $request, $id = null): RedirectResponse
    {
        return $this->assetService->handleAssetCategorySave($request, $id);
    }
    public function adminAssetCategoryDelete($id): RedirectResponse
    {
        return $this->assetService->handleAssetCategoryDelete($id);
    }
    public function adminAssetList(): View
    {
        return $this->assetService->renderAssetList();
    }
    public function adminAssetCreateOrEdit($id = null): View
    {
        return $this->assetService->renderAssetCreateOrEdit($id);
    }
    public function adminAssetSave(Request $request, $id = null): RedirectResponse
    {
        return $this->assetService->handleAssetSave($request, $id);
    }
    public function adminAssetDelete($id): RedirectResponse
    {
        return $this->assetService->handleAssetDelete($id);
    }

    public function adminFacebookCredentials(): View
    {
        return $this->facebookCredentialsService->renderFacebookCredentialsPage();
    }

    public function adminFacebookCredentialsUpdate(Request $request): RedirectResponse
    {
        return $this->facebookCredentialsService->handleFacebookCredentialsUpdate($request);
    }

    public function adminFacebookPages(Request $request): View
    {
        return $this->facebookPageService->renderFacebookPagesPage($request);
    }

    public function adminFacebookPagesSync(): RedirectResponse
    {
        return $this->facebookPageService->handleSyncFromApi();
    }

    public function adminFacebookPageToggleStatus($id): RedirectResponse
    {
        return $this->facebookPageService->handlePageToggleStatus($id);
    }

    public function adminFacebookPageDelete($id): RedirectResponse
    {
        return $this->facebookPageService->handlePageDelete($id);
    }

    public function adminFacebookPageView($id): View
    {
        return $this->facebookPageService->renderPageView($id);
    }

    public function adminFacebookLeadgenForms(Request $request): View
    {
        return $this->facebookLeadgenFormService->renderLeadgenFormsPage($request);
    }

    public function adminFacebookLeadgenFormsSync(): RedirectResponse
    {
        return $this->facebookLeadgenFormService->handleSyncFromApi();
    }

    public function adminFacebookLeadgenFormsSyncPage($pageId): RedirectResponse
    {
        return $this->facebookLeadgenFormService->syncFormForSpecificPage($pageId);
    }

    public function adminFacebookLeadgenFormToggleStatus($id): RedirectResponse
    {
        return $this->facebookLeadgenFormService->handleFormToggleStatus($id);
    }

    public function adminFacebookLeadgenFormDelete($id): RedirectResponse
    {
        return $this->facebookLeadgenFormService->handleFormDelete($id);
    }

    public function adminFacebookLeadgenFormView($id): View
    {
        return $this->facebookLeadgenFormService->renderFormView($id);
    }

    public function adminFacebookLeads(Request $request): View
    {
        return $this->facebookLeadService->renderLeadsPage($request);
    }

    public function adminFacebookLeadsCollect(Request $request): RedirectResponse
    {
        return $this->facebookLeadService->handleCollectLeads($request);
    }

    public function adminFacebookLeadsCollectOptimized(Request $request): RedirectResponse
    {
        return $this->facebookLeadService->handleCollectLeadsOptimized($request);
    }

    public function adminFacebookLeadToggleStatus($id): RedirectResponse
    {
        return $this->facebookLeadService->handleLeadToggleStatus($id);
    }

    public function adminFacebookLeadDelete($id): RedirectResponse
    {
        return $this->facebookLeadService->handleLeadDelete($id);
    }

    public function adminFacebookLeadView($id): View
    {
        return $this->facebookLeadService->renderLeadView($id);
    }

    public function adminFacebookLeadsExport(Request $request)
    {
        return $this->facebookLeadService->exportLeads($request);
    }

    public function adminFacebookLeadsFormFields($formId)
    {
        $fieldNames = $this->facebookLeadService->getFormFieldNames($formId);
        return response()->json($fieldNames);
    }

    public function adminFacebookLeadsFieldValues($fieldName)
    {
        $values = $this->facebookLeadService->getFieldValues($fieldName);
        return response()->json($values);
    }

    public function adminFacebookLeadsFormStats($formId)
    {
        $stats = $this->facebookLeadService->getFormLeadStats($formId);
        return response()->json($stats);
    }
}
