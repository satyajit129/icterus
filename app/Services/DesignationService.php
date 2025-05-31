<?php
namespace App\Services;
use App\Models\Designation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignationService
{
    public function renderDesignationPage(): \Illuminate\View\View
    {
        $designations = Designation::all();
        return view('backend.pages.designation', compact('designations'));
    }

    public function handleDesignationUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $designation = Designation::findOrFail($request->input('id'));
            $designation->update($request->only('name', 'description'));
            return redirect()->route('adminDesignation')->with('success', 'Designation updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating designation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the designation.');
        }
    }
}
