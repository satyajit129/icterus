<?php


namespace App\Services;

use App\Models\Employee;
use App\Models\OfficeExpense;
use App\Models\SalaryExpense;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;

class OfficeExpenseService
{
    public function renderOfficeExpense(): \Illuminate\View\View
    {
        $office_expenses = OfficeExpense::all();
        return view('backend.pages.office_expense', compact('office_expenses'));
    }
    public function renderOfficeExpenseCreateOrEditPage($id = null): \Illuminate\View\View
    {
        $office_expense = null;
        if ($id) {
            $office_expense = OfficeExpense::findOrFail($id);
        }
        return view('backend.pages.office_expense_create_or_edit', compact('office_expense'));
    }
    public function handleOfficeExpenseSave($request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'date' => 'required|date_format:d/m/Y',
                'purpose' => 'required|string|max:255',
                'quantity' => 'nullable|integer|min:1',
                'details' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);
            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
            $office_expense = $id ? OfficeExpense::findOrFail($id) : new OfficeExpense();
            $office_expense->date = $date;
            $office_expense->purpose = $request->purpose;
            $office_expense->quantity = $request->quantity;
            $office_expense->details = $request->details;
            $office_expense->amount = $request->amount;

            $office_expense->save();

            return redirect()->route('adminOfficeExpense')->with('success', 'Office Expense ' . ($id ? 'updated' : 'created') . ' successfully.');

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
    public function handleOfficeExpenseDelete($id):\Illuminate\Http\RedirectResponse
    {
        try {
            $employee = OfficeExpense::findOrFail($id);
            $employee->delete();
            return redirect()->route('adminOfficeExpense')->with('success', 'Office Expense deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderOfficeExpenseView($id): \Illuminate\View\View
    {
        $office_expense = OfficeExpense::findOrFail($id);
        return view('backend.pages.office_expense_view', compact('office_expense'));
    }
}
