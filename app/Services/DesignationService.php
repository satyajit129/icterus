<?php
namespace App\Services;
use App\Models\Designation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DesignationService
{
    public function renderDesignationPage(): \Illuminate\View\View
    {
        $designations = Designation::all();
        return view('backend.pages.designation', compact('designations'));
    }

    public function renderDesignationCreateOrEditPage($id = null): \Illuminate\View\View
    {
        $designation = null;
        if ($id) {
            $designation = Designation::findOrFail($id);
        }
        return view('backend.pages.designation_create_or_edit', compact('designation'));
    }
    public function handleDesignationSave($request, $id = null)
    {
        try {
            $request->validate([
                'designation' => 'required'
            ]);
            $designation = $id ? Designation::findOrFail($id) : new Designation();
            $designation->designation = $request->designation;
            $designation->save();
            return redirect()->route('adminDesignation')->with('success', $id ? 'Data Updated Successfully!' : 'Data Created Successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function handleDesignationDelete($id)
    {
        try {
            $designation = Designation::findOrFail($id);
            $designation->delete();

            return redirect()->route('adminDesignation')->with('success', 'Designation deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

}