<?php

namespace App\Services;

use App\Models\Employee;

class DashboardService
{
    public function renderDashboard():\Illuminate\View\View
    {
        $total_employees = Employee::where('status', 1)->count();
        return view('backend.pages.admin_dashboard',compact('total_employees'));
    }
}

