<?php


namespace App\Services;

use App\Models\Employee;
use App\Models\IncentiveExpense;
use App\Models\SalaryExpense;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class IncentiveExpenseService
{
    public function renderIncentiveExpense(): View
    {
        $incentive_expenses = IncentiveExpense::with('employee.designation','employee.department')->get();
        return view('backend.pages.incentive_expense', compact('incentive_expenses'));
    }
    public function renderIncentiveExpenseCreateOrEditPage($id = null): View
    {
        $incentive_expense = null;
        $employees = Employee::where('status', 1)->get();
        if ($id) {
            $incentive_expense = IncentiveExpense::findOrFail($id);
        }
        return view('backend.pages.incentive_expense_create_or_edit', compact('employees','incentive_expense'));
    }
    public function handleIncentiveExpenseSave($request, $id): RedirectResponse
    {
        // dd($request->all());
        try {
            $request->validate([
                'employee_id'        => 'required|exists:employees,id',
                'name'               => 'required',
                'designation'        => 'required',
                'department'         => 'required',
                'phone_number'       => 'required',
                'account_no'         => 'required',
                'payable_month'      => 'required|date_format:Y-m',
                'sales_count'        => 'required|numeric',
                'sales_amount'       => 'required|numeric',
                'incentive_amount'   => 'required|numeric',
                'payable_amount'     => 'required|numeric',
            ]);

            $incentive_expense = $id ? IncentiveExpense::findOrFail($id) : new IncentiveExpense();
            $incentive_expense->employee_id = $request->employee_id;
           $incentive_expense->payable_month = $request->payable_month . '-01';
            $incentive_expense->sales_count = $request->sales_count;
            $incentive_expense->sales_amount = $request->sales_amount;
            $incentive_expense->incentive_amount = $request->incentive_amount;
            $incentive_expense->payable_amount = $request->payable_amount;
            $incentive_expense->save();
            return redirect()->route('adminIncentiveExpense')->with('success', 'Incentive Expense ' . ($id ? 'updated' : 'created') . ' successfully.');

        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }
    public function handleIncentiveExpenseDelete($id): RedirectResponse
    {
        try {
            $employee = IncentiveExpense::findOrFail($id);
            $employee->delete();
            return redirect()->route('adminIncentiveExpense')->with('success', 'Incentive Expense deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderIncentiveExpenseView($id): View
    {
        $incentive_expense = IncentiveExpense::with('employee', 'employee.designation', 'employee.department')->findOrFail($id);
        return view('backend.pages.incentive_expense_view', compact('incentive_expense'));
    }
}
