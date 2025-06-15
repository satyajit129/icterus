<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyDeal;
use Carbon\Carbon;
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
        $company_deals = CompanyDeal::with('companies')->get();
        // dd($company_deals);
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
        return view('backend.pages.company_deals_view',compact('company_deal'));
    }
}
