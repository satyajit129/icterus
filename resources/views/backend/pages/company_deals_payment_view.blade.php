@extends('backend.layouts.master')

@section('title', 'Company Deals Payment')

@section('custom_css')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printSection, #printSection * {
                visibility: visible;
            }
            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
@endsection


@section('content')
    <div class="page-header">
        <h1 class="page-title">Company Info</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminEmployeeList') }}">Company Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company Info</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Company Deals Payment</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%;">Company</th>
                                    <td style="width: 50%;">{{ $deal_payments->companyDeal->companies->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Payment Date</th>
                                    <td style="width: 50%;">{{ \Carbon\Carbon::parse($deal_payments->payment_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Amount</th>
                                    <td style="width: 50%;">{{ number_format($deal_payments->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Payment Method</th>
                                    <td style="width: 50%;">{{ $deal_payments->payment_method }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Notes</th>
                                    <td style="width: 50%;">{{ $deal_payments->notes ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection
