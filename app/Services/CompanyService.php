<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyDeal;
use App\Models\DealPayment;
use App\Models\Earning;
use App\Models\Employee;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CompanyService
{
    public function renderCompanyList(): View
    {
        $company_lists = Company::all();
        return view('backend.pages.companies', compact('company_lists'));
    }

    public function renderCompanyCreateOrEditPage($id = null): View
    {
        $company = null;
        if ($id) {
            $company = Company::findOrFail($id);
        }
        return view('backend.pages.company_create_or_edit', compact('company'));
    }
    public function handleCompanySave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'ceo_name' => 'required',
                'ceo_phone' => 'nullable',
                'ceo_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'address' => 'nullable',
                'categories' => 'nullable',
            ]);
            $company = $id ? Company::findOrFail($id) : new Company();
            $company->name = $request->name;
            $company->ceo_name = $request->ceo_name;
            $company->ceo_phone = $request->ceo_phone;
            $company->address = $request->address;
            $company->categories = $request->categories;
            if ($request->hasFile('logo')) {
                $extension = $request->file('logo')->getClientOriginalExtension();
                $fileName = 'logo_' . time() . '.' . $extension;
                $request->file('logo')->move(public_path('uploads'), $fileName);
                $company->logo = $fileName;
            }
            if ($request->hasFile('ceo_picture')) {
                $extension = $request->file('ceo_picture')->getClientOriginalExtension();
                $fileName = 'ceo_picture_' . time() . '.' . $extension;
                $request->file('ceo_picture')->move(public_path('uploads'), $fileName);
                $company->ceo_picture = $fileName;
            }
            $company->save();
            return redirect()->route('adminCompanyList')->with('success', $id ? 'Data Updated Successfully!' : 'Data Created Successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function handleCompanyDelete($id): RedirectResponse
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();
            return redirect()->route('adminCompanyList')->with('success', 'Company deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderCompanyView($id): View
    {
        $company = Company::findOrFail($id);
        return view('backend.pages.company_view', compact('company'));
    }
    public function renderCompanyDealsList()
    {
        $company_deals = CompanyDeal::with(['companies', 'dealPayments'])->get();
        return view('backend.pages.company_deals_list', compact('company_deals'));
    }
    public function renderCompanyDealsCreateOrEdit($id = null): View
    {
        $company_deal = null;
        if ($id) {
            $company_deal = CompanyDeal::findOrFail($id);
        }
        $companies = Company::all();
        return view('backend.pages.company_deals_create_or_edit', compact('company_deal', 'companies'));
    }
    public function handleCompanyDealsSave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'date' => 'required|date_format:d/m/Y',
                'company_id' => 'required|exists:companies,id',
                'deals' => 'nullable|string',
                'contract_duration' => 'nullable|integer|min:1',
                'deals_amount' => 'nullable|numeric|min:0',
                'payment_frequency' => 'required|in:weekly,monthly,quarterly,yearly',
            ]);

            $company_deal = $id ? CompanyDeal::findOrFail($id) : new CompanyDeal();
            $company_deal->date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
            $company_deal->company_id = $request->company_id;
            $company_deal->deals = $request->deals ?? null;
            $company_deal->contract_duration = $request->contract_duration ?? null;
            $company_deal->deals_amount = $request->deals_amount ?? null;
            $company_deal->payment_frequency = $request->payment_frequency;
            $company_deal->save();

            return redirect()->route('adminCompanyDealsList')->with('success', $id ? 'Data Updated Successfully!' : 'Data Created Successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function handleCompanyDealsDelete($id): RedirectResponse
    {
        try {
            $company_deal = CompanyDeal::findOrFail($id);
            $company_deal->delete();
            return redirect()->route('adminCompanyDealsList')->with('success', 'Data deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderCompanyDealsView($id): View
    {
        $company_deal = CompanyDeal::with('companies')->findOrFail($id);
        return view('backend.pages.company_deals_view', compact('company_deal'));
    }
    public function renderadminDealsPayment($id): View
    {
        // dd('here');
        $deal_payments = DealPayment::with('companyDeal.companies')
            ->where('company_deal_id', $id)
            ->latest()
            ->get();

        return view('backend.pages.company_deals_payment', compact('deal_payments', 'id'));
    }
    public function renderDealsPaymentCreateOrEdit($request, $id = null): View
    {
        $deal_payment = null;
        if ($id) {
            $deal_payment = DealPayment::findOrFail($id);
        }
        $deal_id = $request->deal_id;
        return view('backend.pages.company_deals_payment_create_or_edit', compact('deal_payment', 'deal_id'));
    }
    public function handleDealsPaymentSave($request, $id = null): RedirectResponse
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'company_deal_id' => 'required|exists:company_deals,id', // assuming you have a company_deals table
                'payment_date' => 'required|date_format:d/m/Y',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $dealPayment = $id ? DealPayment::findOrFail($id) : new DealPayment();
            $dealPayment->company_deal_id = $request->company_deal_id;
            $dealPayment->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->format('Y-m-d');
            $dealPayment->amount = $request->amount;
            $dealPayment->payment_method = $request->payment_method ?? null;
            $dealPayment->notes = $request->notes ?? null;
            $dealPayment->save();

            return redirect()
                ->route('adminDealsPayment', $request->deal_id)
                ->with('success', 'Deal payment saved successfully.');

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
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function handleDealsPaymentDelete($id): RedirectResponse
    {
        try {
            $deals_payment = DealPayment::findOrFail($id);
            $deals_payment->delete();
            return redirect()->back()->with('success', 'Data deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderDealsPaymentView($id): View{
        // dd('here');
        $deal_payments = DealPayment::with('companyDeal.companies')
            ->findOrFail($id);
            // dd($deal_payments);

        return view('backend.pages.company_deals_payment_view', compact('deal_payments'));
    }
    public function renderEarningList(): View
    {
        $earnings = Earning::with('employee', 'companies')->get();
        return view('backend.pages.earnings', compact('earnings'));
    }
    public function renderEarningCreateOrEdit($id = null): View
    {
        $earning = null;
        if ($id) {
            $earning = Earning::with('employee', 'companies')->findOrFail($id);
        }
        $employees = Employee::select('id', 'name')->where('status', 1)->get();
        $companies = Company::select('id', 'name')->get();
        return view('backend.pages.earning_create_or_edit', compact('earning', 'employees', 'companies'));
    }
    public function handleEarningSave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'company_id' => 'required|exists:companies,id',
                'employee_id' => 'required|exists:employees,id',
                'date' => 'nullable|date_format:d/m/Y',
                'payment_method' => 'nullable|string|max:255',
                'phone_number' => 'nullable|string|max:255',
                'paid_amount' => 'nullable|numeric',
                'trnx_id' => 'nullable|string|max:255',
                'sales_status' => 'nullable|string|max:255',
                'customer_number' => 'nullable|string|max:255',
                'deals_amount' => 'nullable|numeric',
                'due_amount' => 'nullable|numeric',
                'product_name' => 'nullable|string|max:255',
                'details' => 'nullable|string',
            ]);

            $earning = $id ? Earning::findOrFail($id) : new Earning();

            $earning->company_id = $request->company_id;
            $earning->employee_id = $request->employee_id;
            $earning->date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
            $earning->payment_method = $request->payment_method;
            $earning->phone_number = $request->phone_number;
            $earning->paid_amount = $request->paid_amount;
            $earning->trnx_id = $request->trnx_id;
            $earning->sales_status = $request->sales_status;
            $earning->customer_number = $request->customer_number;
            $earning->deals_amount = $request->deals_amount;
            $earning->due_amount = $request->due_amount;
            $earning->product_name = $request->product_name;
            $earning->details = $request->details;
            $earning->save();
            return redirect()->route('adminEarningList')->with('success', $id ? 'Earning updated successfully!' : 'Earning created successfully!');

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
    public function handleEarningDelete($id): RedirectResponse
    {
        try {
            $earning = Earning::findOrFail($id);
            $earning->delete();
            return redirect()->route('adminEarningList')->with('success', 'Data deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderEarningView($id): View
    {
        $earning = Earning::with('employee', 'companies')->findOrFail($id);
        return view('backend.pages.earning_view', compact('earning'));
    }

}
