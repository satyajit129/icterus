<?php


namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryExpense;
use Exception;
use Illuminate\Validation\ValidationException;

class SalaryExpenseService
{
    public function renderSalaryExpenseList(): \Illuminate\View\View
    {
        $salary_expenses = SalaryExpense::with('employee.designation','employee.department')->get();
        return view('backend.pages.salary_expense_list', compact('salary_expenses'));
    }
    public function renderSalaryExpenseCreateOrEditPage($id = null): \Illuminate\View\View
    {
        $salary_expense = null;
        $employees = Employee::where('status', 1)->get();
        if ($id) {
            $salary_expense = SalaryExpense::findOrFail($id);
        }
        return view('backend.pages.salary_expense_create_or_edit', compact('employees','salary_expense'));
    }
    public function handleSalaryExpenseSave($request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'employee_id'        => 'required|exists:employees,id',
                'name'               => 'required',
                'designation'        => 'required',
                'department'         => 'required',
                'phone_number'       => 'required',
                'account_no'         => 'required',
                'gross_salary'       => 'required|numeric|min:0',
                'payable_year'       => 'required|digits:4|integer',
                'payable_month'      => 'required|integer|min:1|max:12',
                'total_working_day'  => 'required|integer|min:0|max:31',
                'total_days_in_month'=> 'required|integer|min:28|max:31',
                'festival_bonus'     => 'nullable|numeric|min:0',
                'payable_amount'     => 'required',
            ]);

            $salary_expense = $id ? SalaryExpense::findOrFail($id) : new SalaryExpense();
            $salary_expense->employee_id = $request->employee_id;
            $salary_expense->payable_month = $request->payable_month;
            $salary_expense->payable_year = $request->payable_year;
            $salary_expense->total_working_day = $request->total_working_day;
            $salary_expense->total_days_in_month = $request->total_days_in_month;
            $salary_expense->actual_payable_amount = ($request->payable_amount) - ($request->festival_bonus);
            $salary_expense->festival_bonus = $request->festival_bonus;
            $salary_expense->payable_amount = $request->payable_amount;
            $salary_expense->save();

            return redirect()->route('adminSalaryExpense')->with('success', 'Salary Expense ' . ($id ? 'updated' : 'created') . ' successfully.');

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
    public function handleSalaryExpenseDelete($id){
        try {
            $employee = SalaryExpense::findOrFail($id);
            $employee->delete();
            return redirect()->route('adminSalaryExpense')->with('success', 'Salary Expense deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
}
