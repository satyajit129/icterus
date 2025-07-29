<?php


namespace App\Services;

use App\Models\Employee;
use App\Models\ExpenseCategory;
use App\Models\OfficeExpense;
use App\Models\SalaryExpense;
use App\Models\SalesStatus;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OfficeExpenseService
{
    public function renderOfficeExpense(): View
    {
        $office_expenses = OfficeExpense::with('category')->paginate(20);

        return view('backend.pages.office_expense', compact('office_expenses'));
    }
    public function renderOfficeExpenseCreateOrEditPage($id = null): View
    {
        $expense_categories = ExpenseCategory::all();
        $office_expense = null;
        if ($id) {
            $office_expense = OfficeExpense::findOrFail($id);
        }
        return view('backend.pages.office_expense_create_or_edit', compact('office_expense','expense_categories'));
    }
    public function handleOfficeExpenseSave($request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'date' => 'required|date_format:d/m/Y',
                'purpose' => 'required|string|max:255',
                'quantity' => 'nullable|integer|min:1',
                'details' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);

            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
            $office_expense = $id ? OfficeExpense::findOrFail($id) : new OfficeExpense();
            $office_expense->category_id = $request->category_id;
            $office_expense->date = $date;
            $office_expense->purpose = $request->purpose;
            $office_expense->quantity = $request->quantity;
            $office_expense->details = $request->details;
            $office_expense->amount = $request->amount;

            $office_expense->save();

            return redirect()
                ->route('adminOfficeExpense', ['page' => request()->input('page', 1)])
                ->with('success', 'Office Expense ' . ($id ? 'updated' : 'created') . ' successfully.');


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
    public function handleOfficeExpenseDelete($id): RedirectResponse
    {
        try {
            $employee = OfficeExpense::findOrFail($id);
            $employee->delete();

            return redirect()
                ->route('adminOfficeExpense', ['page' => request('page', 1)])
                ->with('success', 'Office Expense deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function renderOfficeExpenseView($id): View
    {
        $office_expense = OfficeExpense::findOrFail($id);
        return view('backend.pages.office_expense_view', compact('office_expense'));
    }
    public function renderSalesStatus(): View
    {
        $sales_statuses = SalesStatus::all();
        return view('backend.pages.sales_status', compact('sales_statuses'));
    }
    public function renderSalesStatusCreateOrEdit($id = null): View
    {
        $sales_status = $id ? SalesStatus::findOrFail($id) : null;
        return view('backend.pages.sales_status_create_or_edit',compact('sales_status'));
    }
    public function handleSalesStatusSave($request, $id = null): RedirectResponse
    {
        try {
             $request->validate([
                'status' => 'required|unique:sales_statuses,status,' . $id
            ]);
            $sales_status = $id ? SalesStatus::findOrFail($id) : new SalesStatus();
            $sales_status->status = $request->status;
            $sales_status->save();
            return redirect()->route('adminSalesStatus')->with('success', $id ? 'Status Updated Successfully !': 'Status Created Successfully !');
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
    public function handleSalesStatusDelete($id): RedirectResponse
    {
        $sales_status = SalesStatus::findOrFail($id);
        $sales_status->delete();
        return redirect()->back()->with('success', 'Status Deleted Successfully!');
    }
    public function renderExpenseCategory(): View
    {
        $categories = ExpenseCategory::all();
        return view('backend.pages.expense_category_list',compact('categories'));
    }
    public function renderExpenseCategoryCreateOrEdit($id = null): View
    {
        $category = $id ? ExpenseCategory::findOrFail($id) : null;
        return view('backend.pages.expense_category_create_or_edit',compact('category'));
    }
    public function handleExpenseCategorySave($request, $id = null): RedirectResponse
    {
        try {
             $request->validate([
                'name' => 'required|unique:expense_categories,name,' . $id
            ]);
            $category = $id ? ExpenseCategory::findOrFail($id) : new ExpenseCategory();
            $category->name = $request->name;
            $category->save();
            return redirect()->route('adminExpenseCategory')->with('success', $id ? 'Category Update successfully !': 'Category Created Successfully!');
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
    public function handleExpenseCategoryDelete($id): RedirectResponse
    {
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('adminExpenseCategory')->with('success', 'Category Deleted Successfully!');
    }
}
