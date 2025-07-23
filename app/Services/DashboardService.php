<?php

namespace App\Services;

use App\Enum\SalaryStatus;
use App\Models\Earning;
use App\Models\Employee;
use App\Models\IncentiveExpense;
use App\Models\OfficeExpense;
use App\Models\SalaryExpense;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardService
{
    public function renderDashboard($request): View
    {
        $month = $request->input('payable_month');
        $year = $request->input('payable_year');

        $total_employees = Employee::where('status', 1)->count();
        $total_cost = $this->getTotalCost($month, $year);
        $total_earning = $this->getTotalEarning($month, $year);
        $net_profit_or_loss = $total_earning - $total_cost;

        return view('backend.pages.admin_dashboard', compact(
            'total_employees',
            'total_cost',
            'total_earning',
            'net_profit_or_loss'
        ));
    }

    private function getTotalCost($month = null, $year = null): float
    {
        return $this->getSalaryCost($month, $year)
            + $this->getIncentiveCost($month, $year)
            + $this->getOfficeExpense($month, $year);
    }

    private function getTotalEarning($month = null, $year = null): float
    {
        return $this->getPaidEarningAmount($month, $year);
    }

    private function getSalaryCost($month = null, $year = null): float
    {
        $query = SalaryExpense::where('salary_status', SalaryStatus::APPROVED);

        if (!is_null($month) && !is_null($year)) {
            $query->where('payable_month', $month)
                ->where('payable_year', $year);
        }

        return $query->sum('payable_amount');
    }

    private function getIncentiveCost($month = null, $year = null): float
    {
        $query = IncentiveExpense::query();

        if (!is_null($month) && !is_null($year)) {
            $query->whereMonth('payable_month', $month)
                ->whereYear('payable_month', $year);
        }

        return $query->sum('payable_amount');
    }

    private function getOfficeExpense($month = null, $year = null): float
    {
        $query = OfficeExpense::query();

        if (!is_null($month) && !is_null($year)) {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        }

        return $query->sum('amount');
    }

    private function getPaidEarningAmount($month = null, $year = null): float
    {
        $query = Earning::query();

        if (!is_null($month) && !is_null($year)) {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        }

        return $query->sum('paid_amount');
    }
}
