<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\IncentiveExpense;
use App\Models\OfficeExpense;
use App\Models\SalaryExpense;

class DashboardService
{
    public function renderDashboard():\Illuminate\View\View
    {
        $total_employees = Employee::where('status', 1)->count();
        $total_cost = $this->getTotalCost();
        return view('backend.pages.admin_dashboard',compact('total_employees','total_cost'));
    }
    private function getTotalCost(): float
    {
        return $this->getSalaryCost() + $this->getIncentiveCost() + $this->getOfficeExpense();
    }

    private function getSalaryCost(): float
    {
        return SalaryExpense::sum('payable_amount');
    }

    private function getIncentiveCost(): float
    {
        return IncentiveExpense::sum('payable_amount');
    }

    private function getOfficeExpense(): float
    {
        return OfficeExpense::sum('amount');
    }
}

