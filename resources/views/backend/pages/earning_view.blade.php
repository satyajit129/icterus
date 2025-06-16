@extends('backend.layouts.master')

@section('title', 'Earning Info')

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
        <h1 class="page-title">Earning Info</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminEarningList') }}">Earning</a></li>
                <li class="breadcrumb-item active" aria-current="page">Earning Info</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Earning Info</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <!-- Profile Image -->
                    <div class="text-center mb-4">

                    </div>

                    <!-- Earning Info Table -->
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 50%;">Company</th>
                                <td style="width: 50%;">{{ $earning->companies->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ \Carbon\Carbon::parse($earning->date)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Employee</th>
                                <td>{{ $earning->employee->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td>{{ $earning->payment_method }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $earning->phone_number }}</td>
                            </tr>
                            
                            <tr>
                                <th>Transaction ID</th>
                                <td>{{ $earning->trnx_id }}</td>
                            </tr>
                            <tr>
                                <th>Sales Status</th>
                                <td>{{ $earning->sales_status }}</td>
                            </tr>
                            <tr>
                                <th>Customer Number</th>
                                <td>{{ $earning->customer_number }}</td>
                            </tr>
                            <tr>
                                <th>Deals Amount</th>
                                <td>{{ number_format($earning->deals_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>{{ number_format($earning->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Due Amount</th>
                                <td>{{ number_format($earning->due_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Product Name</th>
                                <td>{{ $earning->product_name }}</td>
                            </tr>
                            <tr>
                                <th>Details</th>
                                <td>{{ $earning->details }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection
