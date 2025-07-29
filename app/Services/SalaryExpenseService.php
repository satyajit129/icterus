<?php


namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryExpense;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SalaryExpenseService
{
    public function renderSalaryExpenseList(Request $request): View
    {
        $query = SalaryExpense::with('employee.designation', 'employee.department');

        // Apply filters if present
        if ($request->filled('payable_month')) {
            $query->where('payable_month', $request->payable_month);
        }

        if ($request->filled('payable_year')) {
            $query->where('payable_year', $request->payable_year);
        }

        if ($request->filled('name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('employee_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('id_number', 'like', '%' . $request->employee_id . '%');
            });
        }

        $salary_expenses = $query->paginate(20)->appends($request->all());

        return view('backend.pages.salary_expense_list', compact('salary_expenses'));
    }
    public function renderSalaryExpenseCreateOrEditPage($id = null): View
    {
        $employees = Employee::where('status', 1)->get();
        $salary_expense = $id ? SalaryExpense::findOrFail($id) : null;
        return view('backend.pages.salary_expense_create_or_edit', compact('employees', 'salary_expense'));
    }
    public function handleSalaryExpenseSave($request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'name' => 'required',
                'designation' => 'required',
                'department' => 'required',
                'phone_number' => 'required',
                'account_no' => 'required',
                'gross_salary' => 'required|numeric|min:0',
                'payable_year' => 'required|digits:4|integer',
                'payable_month' => 'required|integer|min:1|max:12',
                'total_working_day' => 'required|integer|min:0|max:31',
                'total_days_in_month' => 'required|integer|min:28|max:31',
                'festival_bonus' => 'nullable|numeric|min:0',
                'extra_charge' => 'nullable|numeric|min:0',
                'payable_amount' => 'required',
            ]);

            $salary_expense = $id ? SalaryExpense::findOrFail($id) : new SalaryExpense();
            $salary_expense->employee_id = $request->employee_id;
            $salary_expense->payable_month = $request->payable_month;
            $salary_expense->gross_salary = $request->gross_salary;
            $salary_expense->payable_year = $request->payable_year;
            $salary_expense->total_working_day = $request->total_working_day;
            $salary_expense->total_days_in_month = $request->total_days_in_month;
            $salary_expense->actual_payable_amount = ($request->payable_amount) - ($request->festival_bonus);
            $salary_expense->festival_bonus = $request->festival_bonus;
            $salary_expense->extra_charge = $request->extra_charge;
            $salary_expense->payable_amount = $request->payable_amount;
            $salary_expense->save();

            return redirect()
                ->route('adminSalaryExpense', ['page' => request('page', 1)])
                ->with('success', 'Salary Expense ' . ($id ? 'updated' : 'created') . ' successfully.');

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
    public function handleSalaryExpenseDelete($id)
    {
        try {
            $employee = SalaryExpense::findOrFail($id);
            $employee->delete();

            return redirect()
                ->route('adminSalaryExpense', ['page' => request('page', 1)])
                ->with('success', 'Salary Expense deleted successfully!');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function renderSalaryExpenseView($id): View
    {
        $salary_expense = SalaryExpense::with('employee', 'employee.designation', 'employee.department')->findOrFail($id);
        return view('backend.pages.salary_expense_view', compact('salary_expense'));
    }
    public function handleSalaryExpenseStatusUpdate($request): RedirectResponse
    {
        try {
            $request->validate([
                'salary_expense_id' => 'required|exists:salary_expenses,id',
                'status' => 'required|in:1,2',
            ]);

            $salary = SalaryExpense::findOrFail($request->salary_expense_id);
            $salary->salary_status = (int) $request->status;
            $salary->save();

            return back()->with('success', 'Salary status updated successfully.');
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
}
