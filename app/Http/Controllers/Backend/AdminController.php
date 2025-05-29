<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function adminDashboard(): \Illuminate\View\View
    {
        return view('backend.pages.admin_dashboard');
    }
    public function adminSettings(): \Illuminate\View\View
    {
        return $this->settingService->renderSettingsPage();
    }
    public function adminSettingsUpdate(Request $request):\Illuminate\Http\RedirectResponse
    {
        return $this->settingService->handleSettingsUpdate($request);
    }


}
