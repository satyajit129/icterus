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
        $salary_expenses = SalaryExpense::with('employee')->get();
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
    public function handleEmployeeSave($request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'id_number' => 'required|string|max:50|unique:employees,id_number,' . $id,
                'name' => 'required|string|max:100',
                'designation_id' => 'required|exists:designations,id',
                'department_id' => 'required|exists:departments,id',
                'phone_number' => 'nullable|string|max:20',
                'account_no' => 'nullable|string|max:50',
                'gross_salary' => 'nullable|numeric|min:0',
                'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
                'address' => 'nullable|string|max:255',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'joining_date' => 'nullable|date_format:d/m/Y',
            ]);
            $employee = $id ? Employee::findOrFail($id) : new Employee();
            $employee->id_number = $request->id_number;
            $employee->name = $request->name;
            $employee->designation_id = $request->designation_id;
            $employee->department_id = $request->department_id;
            $employee->phone_number = $request->phone_number;
            $employee->account_no = $request->account_no;
            $employee->gross_salary = $request->gross_salary;
            $employee->blood_group = $request->blood_group;
            $employee->address = $request->address;

            if ($request->filled('joining_date')) {
                $employee->joining_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->joining_date)->format('Y-m-d');

            }

            if ($request->hasFile('picture')) {
                $picture = $request->picture->getClientOriginalExtension();
                $picture = 'picture_' . time() . '.' . $picture;
                $request->picture->move(public_path('uploads'), $picture);
                $employee->picture = $picture;
            }

            $employee->save();

            return redirect()->route('adminEmployeeList')->with('success', 'Employee ' . ($id ? 'updated' : 'created') . ' successfully.');

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
                ->with('error', 'Validation failed: ' . $e->getMessage());
        }
    }
    public function handleEmployeeDelete($id){
        try {
            $employee = Employee::findOrFail($id);
            $employee->status = 0;
            $employee->save();

            return redirect()->route('adminEmployeeList')->with('success', 'Employee deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
}
