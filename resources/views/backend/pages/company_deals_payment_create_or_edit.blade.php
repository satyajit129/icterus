@extends('backend.layouts.master')

@section('title', 'Company Deals Payment')

@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .table td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Company Deals Payment</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('adminCompanyDealsList', $deal_id) }}">Company
                        Deals</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('adminDealsPayment', request('deal_id')) }}">Company Deals List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company Deals Payment</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Company Deals Payment</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminDealsPaymentSave', $deal_payment->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Hidden Fields --}}
                        <input type="hidden" name="deal_id" value="{{ $deal_id }}">
                        <input type="hidden" name="company_deal_id"
                            value="{{ $deal_payment->company_deal_id ?? request('deal_id') }}">

                        <div class="row">
                            <div class="col-md-12">

                                {{-- Payment Date --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Payment Date</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control fc-datepicker" name="payment_date"
                                            placeholder="DD/MM/YYYY"
                                            value="{{ old('payment_date', isset($deal_payment) ? \Carbon\Carbon::parse($deal_payment->payment_date)->format('d/m/Y') : '') }}"
                                            required autocomplete="off">
                                    </div>
                                </div>

                                {{-- Amount --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Amount</label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.01" class="form-control" name="amount" placeholder="Enter amount"
                                            value="{{ old('amount', $deal_payment->amount ?? '') }}" required>
                                    </div>
                                </div>

                                {{-- Payment Method --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Payment Method</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="payment_method"
                                            placeholder="e.g. Cash, Bank, Cheque"
                                            value="{{ old('payment_method', $deal_payment->payment_method ?? '') }}">
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Notes</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes">{{ old('notes', $deal_payment->notes ?? '') }}</textarea>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="row mb-4">
                                    <div class="col-md-12 d-flex justify-content-end" style="gap: 10px;">
                                        <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-dark">
                                            <i class="fe fe-upload me-2"></i>
                                            {{ isset($deal_payment) ? 'Update' : 'Save' }} Payment
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
