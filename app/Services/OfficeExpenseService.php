<?php


namespace App\Services;

use App\Exports\OfficeExpenseExport;
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
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OfficeExpenseService
{

    public function renderOfficeExpense($request): View
    {
        $query = OfficeExpense::with('category');

        // Filter by category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by purpose (assuming partial match)
        if ($request->filled('purpose')) {
            $query->where('purpose', 'like', '%' . $request->purpose . '%');
        }

        // Filter by date range (assuming date stored in a column named 'date')
        if ($request->filled('date')) {
            // Assuming date range is in format "DD/MM/YYYY - DD/MM/YYYY"
            $dates = explode(' - ', $request->date);
            if (count($dates) == 2) {
                // Convert dates to Y-m-d format
                $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }

        $office_expenses = $query->paginate(20)->appends($request->except('page')); // keep filter params in pagination links
        $expense_categories = ExpenseCategory::all();

        return view('backend.pages.office_expense', compact('office_expenses', 'expense_categories'));
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
    public function renderOfficeExpenseExport($request): BinaryFileResponse
    {
        $data = $request->only(['category_id', 'date', 'purpose']);
        return Excel::download(new OfficeExpenseExport($data), 'office_expense.xlsx');
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
