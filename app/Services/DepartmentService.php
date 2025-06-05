<?php 



namespace App\Services;

use App\Models\Department;
use Exception;
use Illuminate\Validation\ValidationException;

class DepartmentService
{
    public function renderDepartmentPage():\Illuminate\View\View
    {
        $departments = Department::all();
        return view('backend.pages.department',compact('departments'));
    }
    public function renderDepartmentCreateOrEditPage($id = null): \Illuminate\View\View
    {
        $department = null;
        if ($id) {
            $department = Department::findOrFail($id);
        }
        return view('backend.pages.department_create_or_edit', compact('department'));
    }
    public function handleDepartmentSave($request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'department' => 'required',
            ]);
            $department = $id ? Department::findOrFail($id) : new Department();
            $department->department = $request->department;
            $department->save();
             return redirect()->route('adminDepartment')->with('success', $id ? 'Data Updated Successfully!' : 'Data Created Successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }
    public function handleDepartmentDelete($id){
        try {
            $designation = Department::findOrFail($id);
            $designation->delete();

            return redirect()->route('adminDepartment')->with('success', 'Department deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }


}