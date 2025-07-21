<?php

namespace App\Services;

use App\Models\Earning;
use App\Models\Employee;
use App\Models\IncentiveExpense;
use App\Models\OfficeExpense;
use App\Models\SalaryExpense;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardService
{
    public function renderDashboard(): View
    {
        $total_employees = Employee::where('status', 1)->count();
        $total_cost = $this->getTotalCost();
        $total_earning = $this->getTotalEarning();
        $net_profit_or_loss = $total_earning - $total_cost;
        return view('backend.pages.admin_dashboard', compact('total_employees', 'total_cost','total_earning','net_profit_or_loss'));
    }
    private function getTotalCost(): float
    {
        return $this->getSalaryCost() + $this->getIncentiveCost() + $this->getOfficeExpense();
    }
    private function getTotalEarning(): float
    {
        return $this->getPaidEarningAmount();
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
    private function getPaidEarningAmount(): float
    {
        return Earning::sum('paid_amount');
    }
}

