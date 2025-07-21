<?php


namespace App\Services;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;
use Illuminate\Validation\ValidationException;

class EmployeeService
{
    public function renderEmployeeList(): View
    {
        $employees = Employee::with(['designation', 'department'])->where('status', 1)->paginate(20);

        return view('backend.pages.employees', compact('employees'));
    }
    public function renderEmployeeCreateOrEditPage($id = null): View
    {
        $employee = null;
        if ($id) {
            $employee = Employee::findOrFail($id);
        }
        $designations = Designation::all();
        $departments = Department::all();
        return view('backend.pages.employee_create_or_edit', compact('employee', 'designations', 'departments'));
    }
    public function handleEmployeeSave($request, $id): RedirectResponse
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
    public function handleEmployeeDelete($id): RedirectResponse
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->status = 0;
            $employee->save();

            return redirect()->route('adminEmployeeList')->with('success', 'Employee deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function getEmployeeData(Request $request): JsonResponse
    {
        $employee = $request->input('employee_id');

        if ($employee) {
            $employee = Employee::with(['designation', 'department'])->find($employee);

            if ($employee) {
                return response()->json([
                    'status' => 'success',
                    'data' => $employee,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee ID is required',
            ], 400);
        }
    }

    public function seeEmployeeData($id): View
    {
        $employee = Employee::with(['designation', 'department'])->findOrFail($id);
        // dd($employee);
        return view('backend.pages.employee_view', compact('employee'));
    }
}
