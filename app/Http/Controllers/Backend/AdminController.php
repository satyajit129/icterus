<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Services\DesignationService;
use App\Services\SettingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    protected SettingService $settingService;
    protected DesignationService $designationService;

    public function __construct(SettingService $settingService, DesignationService $designationService)
    {
        $this->settingService = $settingService;
        $this->designationService = $designationService;
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
            return redirect()->back()->with('error','Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error','An error occurred: ' . $e->getMessage());
        }
    }
    public function adminDesignation(): \Illuminate\View\View
    {
        return $this->designationService->renderDesignationPage();
    }



}
