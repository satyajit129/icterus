@extends('backend.layouts.master')

@section('title', 'Office Expense Info')

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
        <h1 class="page-title">Office Expense Info</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminEmployeeList') }}">Employees Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Office Expense Info</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Office Expense Information</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <!-- Office Expense Info Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ \Carbon\Carbon::parse($office_expense->date)->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Purpose</th>
                                    <td>{{ $office_expense->purpose }}</td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td>{{ isset($office_expense->quantity) ? $office_expense->quantity : '----' }}</td>
                                </tr>
                                <tr>
                                    <th>Details</th>
                                    <td>{{ $office_expense->details }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ number_format($office_expense->amount, 2) }}</td>
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
