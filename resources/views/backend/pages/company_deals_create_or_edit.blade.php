@extends('backend.layouts.master')

@section('title', isset($company_deal_deal->id) ? 'Company Deal Update' : 'Company Deal Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($company_deal_deal->id))
                Company Deal Update
            @else
                Company Deal Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($company_deal->id))
                        Company Deal Update
                    @else
                        Company Deal Create
                    @endif
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if (isset($company_deal->id))
                            Company Deal Update
                        @else
                            Company Deal Create
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminCompanyDealsSave', $company_deal->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                {{-- Date --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Date</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control fc-datepicker" name="date"
                                            placeholder="DD/MM/YYYY"
                                            value="{{ old('date', isset($company_deal->date) ? \Carbon\Carbon::parse($company_deal->date)->format('d/m/Y') : '') }}"
                                            required autocomplete="off">
                                        @error('date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Company --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Select Company</label>
                                    <div class="col-md-9">
                                        <select name="company_id" id="company_id"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="Choose one" required>
                                            <option value="" disabled
                                                {{ old('company_id', $company_deal->company_id ?? '') ? '' : 'selected' }}>
                                                Choose one</option>
                                            @forelse ($companies as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ old('company_id', $company_deal->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @empty
                                                <option disabled>No data found</option>
                                            @endforelse
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Deals --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Deals</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="deals" placeholder="Enter deal details">{{ old('deals', $company_deal->deals ?? '') }}</textarea>
                                        @error('deals')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Contract Duration --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Contract Duration
                                        <span style="color: red;">(In Month e.g. 1, 2, 3)</span>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="contract_duration"
                                            placeholder="Enter duration in months"
                                            value="{{ old('contract_duration', $company_deal->contract_duration ?? '') }}">
                                        @error('contract_duration')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Deal Amount --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Deals Amount</label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.01" class="form-control" name="deals_amount"
                                            placeholder="Enter deal amount"
                                            value="{{ old('deals_amount', $company_deal->deals_amount ?? '') }}">
                                        @error('deals_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Payment Frequency --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Payment Frequency</label>
                                    <div class="col-md-9">
                                        <select name="payment_frequency" class="form-control" required>
                                            <option value="" disabled>-- Select Frequency --</option>
                                            <option value="weekly"
                                                {{ old('payment_frequency', $company_deal->payment_frequency ?? '') == 'weekly' ? 'selected' : '' }}>
                                                Weekly</option>
                                            <option value="monthly"
                                                {{ old('payment_frequency', $company_deal->payment_frequency ?? '') == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                            <option value="quarterly"
                                                {{ old('payment_frequency', $company_deal->payment_frequency ?? '') == 'quarterly' ? 'selected' : '' }}>
                                                Quarterly</option>
                                            <option value="yearly"
                                                {{ old('payment_frequency', $company_deal->payment_frequency ?? '') == 'yearly' ? 'selected' : '' }}>
                                                Yearly</option>
                                        </select>
                                        @error('payment_frequency')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>
                                            @if (isset($company_deal->id))
                                                Update
                                            @else
                                                Create
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <!-- jQuery UI Datepicker -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endsection
