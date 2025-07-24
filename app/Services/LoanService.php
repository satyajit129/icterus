<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoanService

{
    public function renderLoanList(): View
    {
        $loans = Loan::with('employee', 'loanPayment')->latest()->paginate(10);
        return view('backend.pages.loan_list', compact('loans'));
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
}
