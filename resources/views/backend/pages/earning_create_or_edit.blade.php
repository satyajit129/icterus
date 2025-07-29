@extends('backend.layouts.master')

@section('title', isset($earning->id) ? 'Earning Edit' : 'Earning Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($earning->id)) Earning Edit @else Earning Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminEarningList') }}">Earning</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($earning->id)) Earning Edit
                @else Earning Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">@if(isset($earning->id)) Earning Edit @else Earning Create @endif
                    </h3>
                    <a href="{{ route('adminEarningList') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminEarningSave', $earning->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                {{-- Company --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Company</label>
                                    <div class="col-md-9">
                                        <select name="company_id" id="employee_id"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="Choose one" required>
                                            <option label="Choose one"></option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ old('company_id', $earning->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Date</label>
                                    <div class="col-md-9">
                                        <input type="text" name="date" class="form-control fc-datepicker"
                                            value="{{ old('date', isset($earning->date) ? \Carbon\Carbon::parse($earning->date)->format('d/m/Y') : '') }}"
                                            autocomplete="off" placeholder="DD/MM/YYYY">

                                    </div>
                                </div>

                                {{-- Employee --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Employee</label>
                                    <div class="col-md-9">
                                        <select name="employee_id" id="employee_id"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="Choose one" required>
                                            <option label="Choose one"></option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id', $earning->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Payment Method --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Payment Method</label>
                                    <div class="col-md-9">
                                        <input type="text" name="payment_method" class="form-control"
                                            placeholder="Enter Payment Method"
                                            value="{{ old('payment_method', $earning->payment_method ?? '') }}">
                                    </div>
                                </div>

                                {{-- Phone Number --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Phone Number</label>
                                    <div class="col-md-9">
                                        <input type="text" name="phone_number" class="form-control"
                                            placeholder="Enter Phone Number"
                                            value="{{ old('phone_number', $earning->phone_number ?? '') }}">
                                    </div>
                                </div>

                                

                                {{-- Transaction ID --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Transaction ID</label>
                                    <div class="col-md-9">
                                        <input type="text" name="trnx_id" class="form-control"
                                            placeholder="Enter Transaction ID"
                                            value="{{ old('trnx_id', $earning->trnx_id ?? '') }}">
                                    </div>
                                </div>

                                {{-- Sales Status --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Sales Status</label>
                                    <div class="col-md-9">
                                        <select name="sales_status" class="form-control form-select" required>
                                            <option value="">Choose Status</option>
                                            @forelse ($sales_statuses as $sales_status)
                                                <option value="{{ $sales_status->id }}"{{ isset($earning) && $earning->sales_status == $sales_status->id ? 'selected' : '' }}>{{ $sales_status->status }}</option>
                                            @empty
                                                <p>No Data Found</p>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>


                                {{-- Customer Number --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Customer Number</label>
                                    <div class="col-md-9">
                                        <input type="text" name="customer_number" class="form-control"
                                            placeholder="Enter Customer Number"
                                            value="{{ old('customer_number', $earning->customer_number ?? '') }}">
                                    </div>
                                </div>


                                {{-- Deals Amount --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Deals Amount</label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.01" name="deals_amount" class="form-control"
                                            placeholder="Enter Deals Amount"
                                            value="{{ old('deals_amount', $earning->deals_amount ?? '') }}">
                                    </div>
                                </div>

                                {{-- Paid Amount --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Paid Amount</label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.01" name="paid_amount" class="form-control"
                                            placeholder="Enter Paid Amount"
                                            value="{{ old('paid_amount', $earning->paid_amount ?? '') }}">
                                    </div>
                                </div>

                                {{-- Due Amount --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Due Amount</label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.01" name="due_amount" class="form-control"
                                            placeholder="Enter Due Amount"
                                            value="{{ old('due_amount', $earning->due_amount ?? '') }}">
                                    </div>
                                </div>

                                {{-- Product Name --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Product Name</label>
                                    <div class="col-md-9">
                                        <input type="text" name="product_name" class="form-control"
                                            placeholder="Enter Product Name"
                                            value="{{ old('product_name', $earning->product_name ?? '') }}">
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Details</label>
                                    <div class="col-md-9">
                                        <textarea name="details" class="form-control" rows="3"
                                            placeholder="Enter Details">{{ old('details', $earning->details ?? '') }}</textarea>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>
                                            @if(isset($earning->id)) Update @else Create @endif
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
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endsection