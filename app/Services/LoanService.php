<?php

namespace App\Services;

use App\Exports\LoanExport;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoanService

{
    public function renderLoanList($request = null): View
    {
        $query = Loan::with('employee', 'loanPayment');

        // Apply filters if request is provided
        if ($request) {
            // Employee filter
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Date range filter (loan date)
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
                $query->whereDate('date', '>=', $dateFrom);
            }
            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');
                $query->whereDate('date', '<=', $dateTo);
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        $loans = $query->latest()->paginate(20);

        // Calculate summary data
        $summaryQuery = Loan::with('employee', 'loanPayment');

        // Apply same filters to summary query
        if ($request) {
            if ($request->filled('employee_id')) {
                $summaryQuery->where('employee_id', $request->employee_id);
            }
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
                $summaryQuery->whereDate('date', '>=', $dateFrom);
            }
            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');
                $summaryQuery->whereDate('date', '<=', $dateTo);
            }
            if ($request->filled('status')) {
                $summaryQuery->where('status', $request->status);
            }
        }

        $filteredLoans = $summaryQuery->get();

        // Calculate totals
        $totalAmount = $filteredLoans->sum('amount');
        $totalPaid = $filteredLoans->sum(function($loan) {
            return $loan->loanPayment->sum('amount');
        });
        $totalDue = $totalAmount - $totalPaid;

        $summary = [
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue
        ];

        // Get employees for dropdown
        $employees = Employee::where('status', 1)->orderBy('name')->get();

        return view('backend.pages.loan_list', compact('loans', 'summary', 'employees'));
    }
    public function renderLoanCreateOrEdit($id = null): View
    {
        $employees = Employee::where('status', 1)->get();
        $loan = $id ? Loan::findOrFail($id) : null;
        return view('backend.pages.loan_create_or_edit', compact('loan', 'employees'));
    }
    public function handleLoanSave($request, $id =  null): RedirectResponse
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'date' => 'required|date_format:d/m/Y',
                'amount' => 'required|numeric|min:0',
            ]);

            // Parse the date from d/m/Y to Y-m-d
            $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            // Create or update the loan
            $loan = $id ? Loan::findOrFail($id) : new Loan();
            $loan->employee_id = $request->employee_id;
            $loan->date = $formattedDate;
            $loan->amount = $request->amount;
            $loan->save();

            return redirect()
                ->route('adminLoanList')
                ->with('success', 'Loan ' . ($id ? 'updated' : 'created') . ' successfully.');
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
    public function handleLoanDelete($id): RedirectResponse
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();
        return redirect()->back()->with('success', 'Loan Deleted Successfully!');
    }
    public function handleLoanMakePayment($request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'loan_id' => 'required|exists:loans,id',
                'amount' => 'required|numeric|min:1',
                'payment_date' => 'required|date_format:d/m/Y',
            ]);
            $formattedDate = Carbon::createFromFormat('d/m/Y', $request->payment_date)->format('Y-m-d');
            // Fetch the loan
            $loan = Loan::findOrFail($request->loan_id);

            // Calculate total paid for this loan
            $total_paid = LoanPayment::where('loan_id', $loan->id)->sum('amount');

            $total_paid_with_new = $total_paid + $request->amount;

            if ($total_paid_with_new > $loan->amount) {
                $remaining = $loan->amount - $total_paid;

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Payment exceeds remaining loan balance');
            }
            $payment = $id ? LoanPayment::findOrFail($id) : new LoanPayment();
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->payment_date = $formattedDate;
            $payment->save();

            if ($total_paid_with_new == $loan->amount) {
                $loan->status = 2;
                $loan->save();
            }else{
                $loan->status = 1;
                $loan->save();
            }
            return redirect()
                ->back()
                ->with('success', 'Loan payment Saved successfully.');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function renderLoanPaymentDetails($request): View
    {
        $loan_id = $request->loan_id;
        $loan_payments = LoanPayment::where('loan_id', $loan_id)->get();
        return view('backend.pages.loan_payment_details', compact('loan_payments'));
    }
    public function renderLoanPaymentFetch($request): View
    {
        $payment = LoanPayment::findOrFail($request->id);
        return view('backend.pages.loan_payment_edit', compact('payment'));
    }
    public function handleLoanPaymentUpdate($request): RedirectResponse
    {
        try {
            $request->validate([
                'payment_id'    => 'required|exists:loan_payments,id',
                'amount'        => 'required|numeric|min:0',
                'payment_date'  => 'required|date_format:d/m/Y',
            ]);

            $payment = LoanPayment::with('loan')->findOrFail($request->payment_id);
            $loan = $payment->loan;
            $other_payments_total = LoanPayment::where('loan_id', $loan->id)
                ->where('id', '!=', $payment->id)
                ->sum('amount');

            $max_allowable_amount = $loan->amount - $other_payments_total;

            if ($request->amount > $max_allowable_amount) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Payment exceeds remaining loan balance');
            }
            $payment->amount = $request->amount;
            $payment->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->format('Y-m-d');
            $payment->save();

            $total_paid = $other_payments_total + $request->amount;
            if ($total_paid == $loan->amount) {
                $loan->status = 2;
                $loan->save();
            }else{
                $loan->status = 1;
                $loan->save();
            }
            return redirect()->back()->with('success', 'Loan payment updated successfully.');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function renderLoanExport($request): BinaryFileResponse
    {
        $data = $request->only(['employee_id', 'date_from', 'date_to', 'status']);
        return Excel::download(new LoanExport($data), 'loans.xlsx');
    }
}
