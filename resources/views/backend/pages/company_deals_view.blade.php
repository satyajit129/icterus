@extends('backend.layouts.master')

@section('title', 'Company Deals Info')

@section('custom_css')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printSection,
            #printSection * {
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
        <h1 class="page-title">Company Deals Info</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminCompanyDealsList') }}">Company Deals</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company Deals Info</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Company Deals Info</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <!-- Profile Image -->
                    <div class="text-center mb-4">

                    </div>

                    <!-- Company Deals Info Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%;">Date</th>
                                    <td style="width: 50%;">
                                        {{ \Carbon\Carbon::parse($company_deal->date)->format('d/m/Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Company Name</th>
                                    <td>{{ $company_deal->companies->name ?? 'N/A' }}</td>
                                </tr>
                                @if (isset($company_deal->companies->logo))
                                <tr>
                                    <th>Company Logo</th>
                                    <td>
                                        
                                            <img src="{{ asset('uploads/' . $company_deal->companies->logo) }}" alt="Logo"
                                                width="100">
                                        
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>CEO Name</th>
                                    <td>{{ $company_deal->companies->ceo_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>CEO Phone</th>
                                    <td>{{ $company_deal->companies->ceo_phone ?? 'N/A' }}</td>
                                </tr>
                                @if (isset($company_deal->companies->ceo_picture))
                                    <tr>
                                        <th>CEO Picture</th>
                                        <td>
                                            <img src="{{ asset('uploads/' . $company_deal->companies->ceo_picture) }}"
                                                alt="CEO Picture" width="100">
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Company Address</th>
                                    <td>{{ $company_deal->companies->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Company Categories</th>
                                    <td>{{ $company_deal->companies->categories ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Deals</th>
                                    <td>{{ $company_deal->deals ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Contract Duration (Months)</th>
                                    <td>{{ $company_deal->contract_duration ?? 'N/A' }} Month</td>
                                </tr>
                                <tr>
                                    <th>Deals Amount</th>
                                    <td>{{ number_format($company_deal->deals_amount, 2) ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Frequency</th>
                                    <td>{{ ucfirst($company_deal->payment_frequency) ?? 'N/A' }}</td>
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